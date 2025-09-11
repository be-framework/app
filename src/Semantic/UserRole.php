<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidRoleException;
use Be\Framework\Attribute\Validate;

final class UserRole
{
    #[Validate] 
    public function validate(string $userRole): void
    {
        $validRoles = ['subscriber', 'contributor', 'editor', 'admin'];
        
        if (!in_array($userRole, $validRoles, true)) {
            throw new InvalidRoleException($userRole);
        }
    }
}