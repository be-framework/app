<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\WeakPasswordException;
use Be\Framework\Attribute\Validate;

final class Password
{
    #[Validate] 
    public function validate(string $password): void
    {
        if (strlen($password) < 8) {
            throw new WeakPasswordException('must be at least 8 characters');
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            throw new WeakPasswordException('must contain uppercase letter');
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            throw new WeakPasswordException('must contain lowercase letter');
        }
        
        if (!preg_match('/\d/', $password)) {
            throw new WeakPasswordException('must contain number');
        }
        
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            throw new WeakPasswordException('must contain special character');
        }
    }
}