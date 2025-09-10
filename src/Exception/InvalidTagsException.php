<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid tags
 */
#[Message([
    'en' => 'Invalid tags: {reason}',
    'ja' => '無効なタグ: {reason}'
])]
final class InvalidTagsException extends DomainException
{
    public function __construct(public readonly string $reason)
    {
        parent::__construct("Invalid tags: {$reason}");
    }
}