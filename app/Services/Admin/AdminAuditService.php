<?php

namespace App\Services\Admin;

use App\Models\AdminAction;
use App\Models\AdminUser;
use Illuminate\Http\Request;

class AdminAuditService
{
    /**
     * Log an administrative action for security auditing
     */
    public function log(
        AdminUser $admin,
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?array $details = null,
        ?Request $request = null
    ): AdminAction {
        return AdminAction::create([
            'admin_user_id' => $admin->id,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'details' => $details ? json_encode($details) : null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
