<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\EmptyFirstNameException;
use Be\App\Exception\InvalidFirstNameException;
use Be\Framework\Attribute\Validate;

final class FirstName
{
    #[Validate] 
    public function validate(string $firstName): void
    {
        $trimmed = trim($firstName);
        
        if (empty($trimmed)) {
            throw new EmptyFirstNameException();
        }
        
        if (strlen($trimmed) < 2) {
            throw new InvalidFirstNameException();
        }
        
        if (strlen($trimmed) > 50) {
            throw new InvalidFirstNameException();
        }
        
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/u', $trimmed)) {
            throw new InvalidFirstNameException();
        }
    }
}