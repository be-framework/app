<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid body
 */
#[Message([
    'en' => 'Invalid content body (length: {length}): {reason}',
    'ja' => '無効なコンテンツ本文 (長さ: {length}): {reason}'
])]
final class InvalidBodyException extends DomainException
{
    public function __construct(public readonly int $length, public readonly string $reason)
    {
        parent::__construct("Invalid content body (length: {$length}): {$reason}");
    }
}