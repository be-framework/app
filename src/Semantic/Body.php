<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidBodyException;
use Be\Framework\Attribute\Validate;

final class Body
{
    #[Validate] 
    public function validate(string $body): void
    {
        $trimmed = trim($body);
        
        if (empty($trimmed)) {
            throw new InvalidBodyException(0, 'cannot be empty');
        }
        
        if (strlen($trimmed) < 10) {
            throw new InvalidBodyException(strlen($trimmed), 'must be at least 10 characters');
        }
        
        if (strlen($trimmed) > 50000) {
            throw new InvalidBodyException(strlen($trimmed), 'too long (max 50000 characters)');
        }
    }
}