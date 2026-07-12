<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AuthApiController extends Controller
{
    public function __construct(private OtpService $otp) {}

    public function sendOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'identifier' => 'required|string|max:255',
            'channel' => 'required|in:phone,email',
        ]);

        $identifier = trim($data['identifier']);

        if ($this->identifierAlreadyRegistered($identifier, $data['channel'])) {
            $message = $data['channel'] === 'email'
                ? 'This email address is already registered.'
                : 'This phone number is already registered.';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        try {
            $code = $this->otp->generate($identifier, $data['channel']);
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
            'debug_code' => app()->environment('local') ? $code : null,
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'identifier' => 'required|string|max:255',
            'code' => 'required|string|size:6',
            'password' => 'nullable|string|min:8',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'middle_name' => 'nullable|string|max:100',
        ]);

        $identifier = trim($data['identifier']);

        if (! $this->otp->verify($identifier, $data['code'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 422);
        }

        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        $password = $data['password'] ?? str()->random(32);
        $firstName = $data['first_name'] ?? null;
        $lastName = $data['last_name'] ?? null;
        $middleName = $data['middle_name'] ?? null;
        $displayName = ($firstName && $lastName)
            ? trim(collect([$firstName, $middleName, $lastName])->filter()->join(' '))
            : 'User '.$identifier;

        $user = User::firstOrCreate(
            [$isEmail ? 'email' : 'phone' => $identifier],
            [
                'name' => $displayName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'middle_name' => $middleName,
                'password' => Hash::make($password),
                'role' => 'user',
                'phone_verified' => ! $isEmail,
                'email_verified_at' => $isEmail ? now() : null,
            ]
        );

        $user->update([
            'first_name' => $firstName ?? $user->first_name,
            'last_name' => $lastName ?? $user->last_name,
            'middle_name' => $middleName ?? $user->middle_name,
            'name' => $displayName !== 'User '.$identifier ? $displayName : $user->name,
            'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,
            'phone_verified' => true,
            'is_registered' => true,
            'email_verified_at' => $isEmail ? now() : $user->email_verified_at,
        ]);

        return response()->json([
            'token' => $user->createToken('mobile')->plainTextToken,
            'user' => $this->formatUser($user->fresh()),
        ]);
    }

    public function registerProfile(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
        ]);

        /** @var User $user */
        $user = $request->user();
        $user->update([
            ...$data,
            'name' => trim($data['first_name'].' '.$data['last_name']),
            'is_registered' => true,
        ]);

        return response()->json(['user' => $this->formatUser($user->fresh())]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $this->formatUser($request->user())]);
    }

    private function identifierAlreadyRegistered(string $identifier, string $channel): bool
    {
        if ($channel === 'email') {
            return User::query()
                ->where('email', $identifier)
                ->where('is_registered', true)
                ->exists();
        }

        $normalized = $this->normalizePhone($identifier);

        return User::query()
            ->where('is_registered', true)
            ->whereNotNull('phone')
            ->get(['phone'])
            ->contains(fn (User $user) => $this->normalizePhone((string) $user->phone) === $normalized);
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone) ?? '';

        if (str_starts_with($digits, '0')) {
            return '63'.substr($digits, 1);
        }

        if (! str_starts_with($digits, '63')) {
            return '63'.$digits;
        }

        return $digits;
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'middle_name' => $user->middle_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'is_registered' => $user->is_registered,
            'phone_verified' => $user->phone_verified,
            'donor' => $user->donor,
        ];
    }
}
