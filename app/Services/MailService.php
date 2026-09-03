<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Configure mailer dynamically from DB settings or .env fallbacks.
     */
    public static function configureMailer()
    {
        try {
            $settings = DB::table('settings')->pluck('value', 'key')->toArray();

            $driver = !empty($settings['mail_driver']) ? $settings['mail_driver'] : env('MAIL_MAILER', 'smtp');
            $host = !empty($settings['mail_host']) ? $settings['mail_host'] : env('MAIL_HOST', 'smtpout.secureserver.net');
            $port = !empty($settings['mail_port']) ? $settings['mail_port'] : env('MAIL_PORT', '465');
            $encryption = !empty($settings['mail_encryption']) ? $settings['mail_encryption'] : env('MAIL_ENCRYPTION', 'ssl');
            $username = !empty($settings['mail_username']) ? $settings['mail_username'] : env('MAIL_USERNAME', 'info@tourraja.com');
            $password = !empty($settings['mail_password']) ? $settings['mail_password'] : env('MAIL_PASSWORD', 'Mithil@chandrani');
            $fromAddress = !empty($settings['mail_from_address']) ? $settings['mail_from_address'] : env('MAIL_FROM_ADDRESS', 'info@tourraja.com');
            $fromName = !empty($settings['mail_from_name']) ? $settings['mail_from_name'] : env('MAIL_FROM_NAME', 'Tour Raja');

            config([
                'mail.default' => $driver,
                'mail.mailers.smtp.transport' => $driver === 'none' ? 'smtp' : $driver,
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => $port,
                'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $password,
                'mail.from.address' => $fromAddress,
                'mail.from.name' => $fromName,
            ]);

            Mail::purge($driver);
            Mail::purge('smtp');

            return $driver;
        } catch (\Throwable $e) {
            Log::error("MailService configureMailer exception: " . $e->getMessage());
            return env('MAIL_MAILER', 'smtp');
        }
    }

    /**
     * Send email using a Blade view template.
     */
    public static function sendView($toEmail, $subject, $view, array $data = [])
    {
        $toEmail = trim($toEmail);
        if (empty($toEmail)) {
            Log::warning("MailService sendView: Empty recipient email provided.");
            return false;
        }

        // Attempt 1: Dynamic DB Mail Configuration
        try {
            $driver = self::configureMailer();
            Mail::mailer($driver)->send($view, $data, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)->subject($subject);
            });
            Log::info("MailService sendView successfully sent email to {$toEmail} with subject: '{$subject}' via driver {$driver}");
            return true;
        } catch (\Throwable $e) {
            Log::error("MailService sendView (dynamic DB) failed to {$toEmail}: " . $e->getMessage());
        }

        // Attempt 2: Fallback to direct .env Mail Configuration (same mechanism as OTP Controller)
        try {
            $defaultDriver = env('MAIL_MAILER', 'smtp');
            config([
                'mail.default' => $defaultDriver,
                'mail.mailers.smtp.transport' => $defaultDriver,
                'mail.mailers.smtp.host' => env('MAIL_HOST', 'smtpout.secureserver.net'),
                'mail.mailers.smtp.port' => env('MAIL_PORT', 465),
                'mail.mailers.smtp.encryption' => env('MAIL_ENCRYPTION', 'ssl'),
                'mail.mailers.smtp.username' => env('MAIL_USERNAME', 'info@tourraja.com'),
                'mail.mailers.smtp.password' => env('MAIL_PASSWORD', 'Mithil@chandrani'),
                'mail.from.address' => env('MAIL_FROM_ADDRESS', 'info@tourraja.com'),
                'mail.from.name' => env('MAIL_FROM_NAME', 'Tour Raja'),
            ]);
            Mail::purge('smtp');

            Mail::send($view, $data, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)->subject($subject);
            });
            Log::info("MailService sendView (env fallback) successfully sent email to {$toEmail} with subject: '{$subject}'");
            return true;
        } catch (\Throwable $e2) {
            Log::error("MailService sendView (env fallback) failed to {$toEmail}: " . $e2->getMessage());
            return false;
        }
    }

    /**
     * Send email using raw HTML body string.
     */
    public static function sendHtml($toEmail, $subject, $htmlBody)
    {
        $toEmail = trim($toEmail);
        if (empty($toEmail)) return false;

        try {
            $driver = self::configureMailer();
            Mail::mailer($driver)->html($htmlBody, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)->subject($subject);
            });
            return true;
        } catch (\Throwable $e) {
            Log::error("MailService sendHtml failed to {$toEmail}: " . $e->getMessage());
            
            // Fallback to .env config
            try {
                Mail::html($htmlBody, function ($message) use ($toEmail, $subject) {
                    $message->to($toEmail)->subject($subject);
                });
                return true;
            } catch (\Throwable $e2) {
                Log::error("MailService sendHtml fallback failed to {$toEmail}: " . $e2->getMessage());
                return false;
            }
        }
    }
}
