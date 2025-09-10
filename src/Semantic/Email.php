<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidEmailException;
use Be\Framework\Attribute\Validate;

final class Email
{
    #[Validate]
    public function validate(string $email): void
    {
        if (empty(trim($email))) {
            throw new InvalidEmailException('', 'cannot be empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException($email, 'invalid format');
        }

        if (strlen($email) > 254) {
            throw new InvalidEmailException($email, 'too long (max 254 characters)');
        }
    }

    public function validateConfirmEmail(string $email, string $confirmEmail): void
    {
        if ($email !== $confirmEmail) {
            throw new InvalidEmailException($confirmEmail, 'does not match the original email');
        }
    }
}
