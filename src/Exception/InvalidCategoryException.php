<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid category
 */
#[Message([
    'en' => 'Invalid category "{category}": {reason}',
    'ja' => '無効なカテゴリ"{category}": {reason}'
])]
final class InvalidCategoryException extends DomainException
{
    public function __construct(public readonly string $category, public readonly string $reason)
    {
        parent::__construct("Invalid category \"{$category}\": {$reason}");
    }
}