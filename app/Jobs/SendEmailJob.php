<?php

namespace App\Jobs;

use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
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
            $to = $this->data['to'] ?? null;
            $subject = $this->data['subject'] ?? 'No Subject';
            $body = $this->data['body'] ?? '';
            $cc = $this->data['cc'] ?? [];
            $bcc = $this->data['bcc'] ?? [];
            $attachments = $this->data['attachments'] ?? [];
            $templateSlug = $this->data['template_slug'] ?? null;
            $modelType = $this->data['model_type'] ?? null;
            $modelId = $this->data['model_id'] ?? null;

            if (!$to) {
                Log::warning('SendEmailJob: No recipient specified');
                return;
            }

            if ($templateSlug) {
                $template = EmailTemplate::where('slug', $templateSlug)->first();
                if ($template) {
                    $subject = $template->subject;
                    $body = $template->body;
                    foreach ($this->data['placeholders'] ?? [] as $key => $value) {
                        $subject = str_replace('{{' . $key . '}}', $value, $subject);
                        $body = str_replace('{{' . $key . '}}', $value, $body);
                    }
                }
            }

            Mail::html($body, function ($message) use ($to, $subject, $cc, $bcc, $attachments) {
                $message->to($to)
                    ->subject($subject);

                if (!empty($cc)) {
                    $message->cc($cc);
                }
                if (!empty($bcc)) {
                    $message->bcc($bcc);
                }
                foreach ($attachments as $attachment) {
                    if (isset($attachment['path']) && file_exists($attachment['path'])) {
                        $message->attach($attachment['path'], [
                            'as' => $attachment['name'] ?? basename($attachment['path']),
                            'mime' => $attachment['mime'] ?? 'application/octet-stream',
                        ]);
                    }
                }
            });

            EmailLog::create([
                'recipient' => is_array($to) ? implode(', ', $to) : $to,
                'subject' => $subject,
                'body' => $body,
                'cc' => is_array($cc) ? implode(', ', $cc) : $cc,
                'bcc' => is_array($bcc) ? implode(', ', $bcc) : $bcc,
                'status' => 'sent',
                'sent_at' => now(),
                'model_type' => $modelType,
                'model_id' => $modelId,
            ]);
        } catch (\Exception $e) {
            Log::error('SendEmailJob failed', [
                'to' => $this->data['to'] ?? 'unknown',
                'subject' => $this->data['subject'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            EmailLog::create([
                'recipient' => is_array($this->data['to'] ?? []) ? implode(', ', $this->data['to']) : ($this->data['to'] ?? 'unknown'),
                'subject' => $this->data['subject'] ?? 'No Subject',
                'body' => $this->data['body'] ?? '',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'sent_at' => now(),
            ]);
        }
    }
}
