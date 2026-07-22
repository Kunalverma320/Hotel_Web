<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'Hotel Management System', 'group' => 'general', 'type' => 'string'],
            ['key' => 'app_description', 'value' => 'Enterprise Hotel Management System', 'group' => 'general', 'type' => 'string'],
            ['key' => 'app_url', 'value' => config('app.url'), 'group' => 'general', 'type' => 'string'],
            ['key' => 'company_name', 'value' => 'Luxury Hotels International', 'group' => 'company', 'type' => 'string'],
            ['key' => 'company_email', 'value' => 'info@luxuryhotels.com', 'group' => 'company', 'type' => 'string'],
            ['key' => 'company_phone', 'value' => '+1-555-0100', 'group' => 'company', 'type' => 'string'],
            ['key' => 'company_address', 'value' => '100 Luxury Avenue, New York, NY 10001', 'group' => 'company', 'type' => 'string'],
            ['key' => 'default_currency', 'value' => 'USD', 'group' => 'localization', 'type' => 'string'],
            ['key' => 'default_language', 'value' => 'en', 'group' => 'localization', 'type' => 'string'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'group' => 'localization', 'type' => 'string'],
            ['key' => 'time_format', 'value' => 'H:i', 'group' => 'localization', 'type' => 'string'],
            ['key' => 'timezone', 'value' => 'America/New_York', 'group' => 'localization', 'type' => 'string'],
            ['key' => 'week_starts_on', 'value' => 'monday', 'group' => 'localization', 'type' => 'string'],
            ['key' => 'tax_label', 'value' => 'VAT', 'group' => 'tax', 'type' => 'string'],
            ['key' => 'tax_rate', 'value' => '18', 'group' => 'tax', 'type' => 'string'],
            ['key' => 'tax_number', 'value' => 'TAX-98765-001', 'group' => 'tax', 'type' => 'string'],
            ['key' => 'invoice_prefix', 'value' => 'INV-', 'group' => 'invoice', 'type' => 'string'],
            ['key' => 'invoice_terms', 'value' => 'Payment due within 30 days.', 'group' => 'invoice', 'type' => 'string'],
            ['key' => 'invoice_footer', 'value' => 'Thank you for your business!', 'group' => 'invoice', 'type' => 'string'],
            ['key' => 'invoice_logo', 'value' => '', 'group' => 'invoice', 'type' => 'string'],
            ['key' => 'default_check_in_time', 'value' => '15:00', 'group' => 'hotel', 'type' => 'string'],
            ['key' => 'default_check_out_time', 'value' => '11:00', 'group' => 'hotel', 'type' => 'string'],
            ['key' => 'early_check_in_fee', 'value' => '25', 'group' => 'hotel', 'type' => 'string'],
            ['key' => 'late_check_out_fee', 'value' => '50', 'group' => 'hotel', 'type' => 'string'],
            ['key' => 'allow_online_booking', 'value' => 'true', 'group' => 'booking', 'type' => 'boolean'],
            ['key' => 'max_advance_booking_days', 'value' => '365', 'group' => 'booking', 'type' => 'integer'],
            ['key' => 'min_advance_booking_hours', 'value' => '2', 'group' => 'booking', 'type' => 'integer'],
            ['key' => 'cancellation_policy', 'value' => 'Free cancellation up to 24 hours before check-in.', 'group' => 'booking', 'type' => 'string'],
            ['key' => 'booking_prefix', 'value' => 'BK-', 'group' => 'booking', 'type' => 'string'],
            ['key' => 'maintenance_mode', 'value' => 'false', 'group' => 'system', 'type' => 'boolean'],
            ['key' => 'debug_mode', 'value' => 'false', 'group' => 'system', 'type' => 'boolean'],
            ['key' => 'items_per_page', 'value' => '15', 'group' => 'system', 'type' => 'integer'],
            ['key' => 'auto_backup', 'value' => 'true', 'group' => 'system', 'type' => 'boolean'],
            ['key' => 'backup_frequency', 'value' => 'daily', 'group' => 'system', 'type' => 'string'],
            ['key' => 'max_backups', 'value' => '30', 'group' => 'system', 'type' => 'integer'],
            ['key' => 'smtp_host', 'value' => '', 'group' => 'email', 'type' => 'string'],
            ['key' => 'smtp_port', 'value' => '587', 'group' => 'email', 'type' => 'integer'],
            ['key' => 'smtp_username', 'value' => '', 'group' => 'email', 'type' => 'string'],
            ['key' => 'smtp_password', 'value' => '', 'group' => 'email', 'type' => 'string'],
            ['key' => 'smtp_encryption', 'value' => 'tls', 'group' => 'email', 'type' => 'string'],
            ['key' => 'smtp_from_address', 'value' => 'noreply@hotelms.com', 'group' => 'email', 'type' => 'string'],
            ['key' => 'smtp_from_name', 'value' => 'Hotel Management System', 'group' => 'email', 'type' => 'string'],
            ['key' => 'sms_provider', 'value' => 'twilio', 'group' => 'sms', 'type' => 'string'],
            ['key' => 'sms_api_key', 'value' => '', 'group' => 'sms', 'type' => 'string'],
            ['key' => 'sms_sender_id', 'value' => 'HOTELMS', 'group' => 'sms', 'type' => 'string'],
            ['key' => 'whatsapp_enabled', 'value' => 'false', 'group' => 'whatsapp', 'type' => 'boolean'],
            ['key' => 'whatsapp_business_phone', 'value' => '', 'group' => 'whatsapp', 'type' => 'string'],
            ['key' => 'payment_gateway', 'value' => 'stripe', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'stripe_key', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'stripe_secret', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'paypal_client_id', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'paypal_secret', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'theme_primary_color', 'value' => '#1a73e8', 'group' => 'theme', 'type' => 'string'],
            ['key' => 'theme_secondary_color', 'value' => '#34a853', 'group' => 'theme', 'type' => 'string'],
            ['key' => 'theme_sidebar_color', 'value' => '#1e293b', 'group' => 'theme', 'type' => 'string'],
            ['key' => 'theme_font', 'value' => 'Inter', 'group' => 'theme', 'type' => 'string'],
            ['key' => 'enable_registration', 'value' => 'true', 'group' => 'security', 'type' => 'boolean'],
            ['key' => 'two_factor_auth', 'value' => 'false', 'group' => 'security', 'type' => 'boolean'],
            ['key' => 'password_min_length', 'value' => '8', 'group' => 'security', 'type' => 'integer'],
            ['key' => 'session_lifetime', 'value' => '120', 'group' => 'security', 'type' => 'integer'],
            ['key' => 'max_login_attempts', 'value' => '5', 'group' => 'security', 'type' => 'integer'],
            ['key' => 'lockout_duration', 'value' => '15', 'group' => 'security', 'type' => 'integer'],
            ['key' => 'google_maps_api_key', 'value' => '', 'group' => 'integration', 'type' => 'string'],
            ['key' => 'google_analytics_id', 'value' => '', 'group' => 'integration', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
