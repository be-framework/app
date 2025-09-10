<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\EmptyLastNameException;
use Be\App\Exception\LastNameTooShortException;
use Be\App\Exception\LastNameTooLongException;
use Be\App\Exception\LastNameInvalidCharactersException;
use Be\Framework\Attribute\Validate;

final class LastName
{
    #[Validate] 
    public function validate(string $lastName): void
    {
        $trimmed = trim($lastName);
        
        if (empty($trimmed)) {
            throw new EmptyLastNameException($lastName);
        }
        
        if (strlen($trimmed) < 2) {
            throw new LastNameTooShortException($lastName);
        }
        
        if (strlen($trimmed) > 50) {
            throw new LastNameTooLongException($lastName);
        }
        
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/u', $trimmed)) {
            throw new LastNameInvalidCharactersException($lastName);
        }
    }
}