<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidRoleException;
use Be\Framework\Attribute\Validate;

final class Role
{
    private const ALLOWED_ROLES = [
        'admin', 'editor', 'contributor', 'viewer', 'moderator'
    ];

    #[Validate] 
    public function validate(string $role): void
    {
        $normalized = strtolower(trim($role));
        
        if (empty($normalized)) {
            throw new InvalidRoleException();
        }
        
        if (!in_array($normalized, self::ALLOWED_ROLES, true)) {
            throw new InvalidRoleException();
        }
    }
}