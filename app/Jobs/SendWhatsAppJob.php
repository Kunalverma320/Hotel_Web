<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\WhatsappLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppJob implements ShouldQueue
{
    use Queueable;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        try {
            $phone = $this->data['phone'] ?? null;
            $message = $this->data['message'] ?? '';
            $templateName = $this->data['template_name'] ?? null;
            $templateParams = $this->data['template_parameters'] ?? [];
            $mediaUrl = $this->data['media_url'] ?? null;
            $modelType = $this->data['model_type'] ?? null;
            $modelId = $this->data['model_id'] ?? null;

            if (!$phone) {
                Log::warning('SendWhatsAppJob: No phone number specified');
                return;
            }

            $apiUrl = $this->data['api_url'] ?? env('WHATSAPP_API_URL');
            $apiToken = $this->data['api_token'] ?? env('WHATSAPP_API_TOKEN');
            $phoneNumberId = $this->data['phone_number_id'] ?? env('WHATSAPP_PHONE_NUMBER_ID');

            if (!$apiUrl || !$apiToken || !$phoneNumberId) {
                Log::warning('WhatsApp not configured');
                return;
            }

            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => $templateName ? 'template' : 'text',
            ];

            if ($templateName) {
                $payload['type'] = 'template';
                $payload['template'] = [
                    'name' => $templateName,
                    'language' => ['code' => $this->data['language'] ?? 'en'],
                    'components' => [],
                ];
                if (!empty($templateParams)) {
                    $payload['template']['components'][] = [
                        'type' => 'body',
                        'parameters' => array_map(fn ($p) => ['type' => 'text', 'text' => $p], $templateParams),
                    ];
                }
            } elseif ($mediaUrl) {
                $payload['type'] = 'image';
                $payload['image'] = ['link' => $mediaUrl];
                $payload['caption'] = $message;
            } else {
                $payload['text'] = ['body' => $message];
            }

            $response = Http::withToken($apiToken)
                ->post("{$apiUrl}/{$phoneNumberId}/messages", $payload);

            $status = $response->successful() ? 'sent' : 'failed';
            $responseBody = $response->json();

            WhatsappLog::create([
                'recipient' => $phone,
                'message' => $message,
                'template_name' => $templateName,
                'status' => $status,
                'response' => json_encode($responseBody),
                'sent_at' => now(),
                'model_type' => $modelType,
                'model_id' => $modelId,
            ]);

            if (!$response->successful()) {
                Log::error('WhatsApp send failed', [
                    'phone' => $phone,
                    'response' => $responseBody,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('SendWhatsAppJob failed', [
                'phone' => $this->data['phone'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            WhatsappLog::create([
                'recipient' => $this->data['phone'] ?? 'unknown',
                'message' => $this->data['message'] ?? '',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
