<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for weak password
 */
#[Message([
    'en' => 'Password does not meet security requirements: {reason}',
    'ja' => 'パスワードがセキュリティ要件を満たしていません: {reason}'
])]
final class WeakPasswordException extends DomainException
{
    public function __construct(public readonly string $reason)
    {
        parent::__construct("Password does not meet security requirements: {$reason}");
    }
}