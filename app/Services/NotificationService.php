<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function sendEmail(string $to, string $template, array $data): bool
    {
        try {
            Mail::send("emails.{$template}", $data, function ($message) use ($to) {
                $message->to($to);
            });

            $this->logNotification('email', $to, $template, $data);

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function sendSms(string $phone, string $template, array $data): bool
    {
        try {
            $message = $this->renderSmsTemplate($template, $data);

            // Integration point for SMS provider (Twilio, MSG91, etc.)
            // Http::post(config('services.sms.api_url'), [
            //     'phone' => $phone,
            //     'message' => $message,
            // ]);

            $this->logNotification('sms', $phone, $template, $data);

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function sendWhatsApp(string $phone, string $template, array $data): bool
    {
        try {
            $message = $this->renderSmsTemplate($template, $data);

            // Integration point for WhatsApp API
            // Http::post(config('services.whatsapp.api_url'), [
            //     'phone' => $phone,
            //     'message' => $message,
            // ]);

            $this->logNotification('whatsapp', $phone, $template, $data);

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function sendPushNotification(string $title, string $body, string $target, array $data = []): bool
    {
        try {
            // Integration point for push notification service (Firebase, OneSignal, etc.)
            // Http::post(config('services.push.api_url'), [
            //     'title' => $title,
            //     'body' => $body,
            //     'target' => $target,
            //     'data' => $data,
            // ]);

            $this->logNotification('push', $target, 'push', array_merge($data, [
                'title' => $title,
                'body' => $body,
            ]));

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    protected function renderSmsTemplate(string $template, array $data): string
    {
        $message = config("sms_templates.{$template}", $template);

        foreach ($data as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        return $message;
    }

    protected function logNotification(string $type, string $recipient, string $template, array $data): void
    {
        Notification::create([
            'type' => $type,
            'recipient' => $recipient,
            'template' => $template,
            'data' => $data,
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }
}
