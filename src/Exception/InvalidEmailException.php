<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid email
 */
#[Message([
    'en' => 'Invalid email address "{email}": {reason}',
    'ja' => '無効なメールアドレス"{email}": {reason}'
])]
final class InvalidEmailException extends DomainException
{
    public function __construct(public readonly string $email, public readonly string $reason)
    {
        parent::__construct("Invalid email address \"{$email}\": {$reason}");
    }
}