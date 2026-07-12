<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class OtpService
{
    public function generate(string $identifier, string $channel = 'phone'): string
    {
        OtpCode::query()
            ->where('identifier', $identifier)
            ->where('used', false)
            ->update(['used' => true]);

        // Generate 6-digit OTP
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Log::info('Generated OTP', [
            'identifier' => $identifier,
            'code' => $code,
        ]);

        $otp = OtpCode::create([
            'identifier' => $identifier,
            'code' => $code,
            'channel' => $channel,
            'expires_at' => now()->addMinutes(3),
        ]);

        try {
            if ($channel === 'email') {
                $this->sendEmailOtp($identifier, $code);
            } else {
                $this->sendSmsOtp($identifier, $code);
            }
        } catch (RuntimeException $e) {
            $otp->update(['used' => true]);
            throw $e;
        }

        return $code;
    }

    private function sendSmsOtp(string $phone, string $code): void
    {
        $uid = env('SMSAPI_UID');
        $apiKey = env('SMSAPI_API_KEY');
        $baseUrl = rtrim(env('SMSAPI_BASE_URL', 'https://smsapi.neilian.dev/send'), '/');

        Log::info('SMSAPI Send OTP Request', [
            'phone' => $phone,
            'code' => $code,
            'base_url' => $baseUrl,
            'uid_exists' => !empty($uid),
            'api_key_exists' => !empty($apiKey),
        ]);

        if (empty($uid) || empty($apiKey)) {
            Log::warning('SMSAPI_UID or SMSAPI_API_KEY not set');
            throw new RuntimeException('SMS service is not configured. Please set SMSAPI_UID and SMSAPI_API_KEY in .env file.');
        }

        // Format phone number for SMSAPI.
        // SMSAPI requires a Philippine mobile recipient starting with +639.
        $formattedPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($formattedPhone, '0')) {
            $formattedPhone = '+63' . substr($formattedPhone, 1);
        } elseif (str_starts_with($formattedPhone, '63')) {
            $formattedPhone = '+' . $formattedPhone;
        } else {
            $formattedPhone = '+63' . $formattedPhone;
        }

        if (!preg_match('/^\+639\d{9}$/', $formattedPhone)) {
            Log::warning('SMSAPI invalid recipient phone format', ['phone' => $phone, 'formatted_phone' => $formattedPhone]);
            throw new RuntimeException('SMS recipient must be a Philippine mobile number starting with +639.');
        }

        $message = sprintf('Your SmartBlood verification code is %s. Expires in 3 minutes.', $code);

        try {
            $payload = [
                'uid' => $uid,
                'phone' => $formattedPhone,
                'message' => $message,
            ];

            Log::info('SMSAPI Request Payload', [
                'uid_exists' => !empty($uid),
                'phone' => $formattedPhone,
                'message' => $message,
            ]);

            $response = Http::timeout(10)
                ->asJson()
                ->withHeaders([
                    'x-api-key' => $apiKey,
                    'Accept' => 'application/json',
                ])
                ->post($baseUrl, $payload);

            Log::info('SMSAPI Full Response', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if (!$response->successful()) {
                $errorMessage = $response->body() ?: 'Failed to send SMS verification code. Please try again.';
                Log::error('SMSAPI Error Response (HTTP)', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new RuntimeException($errorMessage);
            }

            // Check if result.error is not 0
            $responseBody = $response->json();
            if (isset($responseBody['result']['error']) && $responseBody['result']['error'] != 0) {
                $errorDetails = [
                    'error_code' => $responseBody['result']['error'],
                    'status' => $responseBody['result']['sent'] ?? 'unknown',
                    'note' => $responseBody['result']['note'] ?? null,
                ];
                $errorMessage = 'SMS Error: Code ' . $errorDetails['error_code'] . ' - ' . ($errorDetails['note'] ?? $errorDetails['status']);
                Log::error('SMSAPI Error in Response Body', $responseBody);
                throw new RuntimeException($errorMessage);
            }
        } catch (RuntimeException $e) {
            Log::error('RuntimeException in sendSmsOtp', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('General Exception in sendSmsOtp', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new RuntimeException($e->getMessage() ?: 'Failed to send SMS verification code. Please try again.');
        }
    }

    private function sendEmailOtp(string $email, string $code): void
    {
        Log::info('Email Send OTP Request', [
            'email' => $email,
            'code' => $code,
        ]);

        $body = implode("\n", [
            'SmartBlood Verification',
            '',
            'Your verification code is:',
            '',
            $code,
            '',
            'This code expires in 3 minutes.',
            '',
            'Do not share this code with anyone.',
            '',
            'Thank you,',
            'SmartBlood Team',
        ]);

        try {
            Mail::raw($body, function ($message) use ($email) {
                $message->to($email)
                    ->subject('SmartBlood Verification Code');
            });
            Log::info('Email Send OTP Success');
        } catch (\Exception $e) {
            Log::error('Email Send OTP Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new RuntimeException('Failed to send verification email. Please check your email address and try again.');
        }
    }

    public function verify(string $identifier, string $code): bool
    {
        Log::info('OTP Verification Attempt', [
            'identifier' => $identifier,
            'code' => $code,
        ]);

        $otp = OtpCode::query()
            ->where('identifier', $identifier)
            ->where('used', false)
            ->latest()
            ->first();

        $isValid = $otp && $otp->isValid($code);

        Log::info('OTP Verification Result', [
            'identifier' => $identifier,
            'code' => $code,
            'is_valid' => $isValid,
        ]);

        if ($isValid) {
            $otp->update(['used' => true]);
        }

        return $isValid;
    }
}
