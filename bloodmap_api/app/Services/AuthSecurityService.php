<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;

class AuthSecurityService
{
    public function recordLoginEvent(?User $user, string $event, bool $successful, Request $request, array $metadata = []): void
    {
        LoginHistory::create([
            'user_id' => $user?->id,
            'event' => $event,
            'successful' => $successful,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => $metadata,
        ]);
    }

    public function recordAudit(?User $user, string $action, string $message = null, array $metadata = [], ?string $targetType = null, ?int $targetId = null): void
    {
        AuditLog::create([
            'user_id' => $user?->id,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'action' => $action,
            'message' => $message,
            'metadata' => $metadata,
        ]);
    }

    public function isLocked(User $user): bool
    {
        return $user->locked_until !== null && $user->locked_until->isFuture();
    }

    public function incrementFailedLoginAttempts(User $user, Request $request): void
    {
        $attempts = $user->failed_login_attempts + 1;
        $data = ['failed_login_attempts' => $attempts];

        if ($attempts >= 5) {
            $data['locked_until'] = now()->addMinutes(15);
            $data['failed_login_attempts'] = 0;
            $this->recordAudit($user, 'account.locked', 'User locked after repeated failed login attempts.', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        $user->update($data);
    }

    public function resetFailedLoginAttempts(User $user): void
    {
        $user->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }
}
