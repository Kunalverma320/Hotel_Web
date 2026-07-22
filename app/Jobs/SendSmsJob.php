<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\SmsLog;
use App\Models\SmsTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
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
            $templateSlug = $this->data['template_slug'] ?? null;
            $modelType = $this->data['model_type'] ?? null;
            $modelId = $this->data['model_id'] ?? null;

            if (!$phone) {
                Log::warning('SendSmsJob: No phone number specified');
                return;
            }

            if ($templateSlug) {
                $template = SmsTemplate::where('slug', $templateSlug)->first();
                if ($template) {
                    $message = $template->body;
                    foreach ($this->data['placeholders'] ?? [] as $key => $value) {
                        $message = str_replace('{{' . $key . '}}', $value, $message);
                    }
                }
            }

            $provider = $this->data['provider'] ?? env('SMS_PROVIDER', 'twilio');

            $response = match ($provider) {
                'twilio' => $this->sendViaTwilio($phone, $message),
                'nexmo' => $this->sendViaNexmo($phone, $message),
                default => $this->sendViaCustom($phone, $message),
            };

            SmsLog::create([
                'recipient' => $phone,
                'message' => $message,
                'provider' => $provider,
                'status' => $response ? 'sent' : 'failed',
                'response' => is_string($response) ? $response : json_encode($response),
                'sent_at' => now(),
                'model_type' => $modelType,
                'model_id' => $modelId,
            ]);
        } catch (\Exception $e) {
            Log::error('SendSmsJob failed', [
                'phone' => $this->data['phone'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            SmsLog::create([
                'recipient' => $this->data['phone'] ?? 'unknown',
                'message' => $this->data['message'] ?? '',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    protected function sendViaTwilio(string $phone, string $message): bool
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_FROM');

        if (!$sid || !$token) {
            Log::warning('Twilio not configured');
            return false;
        }

        $response = Http::withBasicAuth($sid, $token)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => $from,
                'To' => $phone,
                'Body' => $message,
            ]);

        return $response->successful();
    }

    protected function sendViaNexmo(string $phone, string $message): bool
    {
        $apiKey = env('NEXMO_API_KEY');
        $apiSecret = env('NEXMO_API_SECRET');
        $from = env('NEXMO_FROM', 'Hotel');

        if (!$apiKey || !$apiSecret) {
            Log::warning('Nexmo not configured');
            return false;
        }

        $response = Http::post('https://rest.nexmo.com/sms/json', [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'from' => $from,
            'to' => $phone,
            'text' => $message,
        ]);

        return $response->successful();
    }

    protected function sendViaCustom(string $phone, string $message): bool
    {
        $url = $this->data['api_url'] ?? env('SMS_API_URL');
        if (!$url) {
            Log::warning('Custom SMS provider not configured');
            return false;
        }

        $payload = $this->data['payload'] ?? [
            'phone' => $phone,
            'message' => $message,
        ];

        $response = Http::withHeaders($this->data['headers'] ?? [])
            ->post($url, $payload);

        return $response->successful();
    }
}
