<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid publish date
 */
#[Message([
    'en' => 'Invalid publish date "{date}": {reason}',
    'ja' => '無効な公開日"{date}": {reason}'
])]
final class InvalidPublishDateException extends DomainException
{
    public function __construct(public readonly string $date, public readonly string $reason)
    {
        parent::__construct("Invalid publish date \"{$date}\": {$reason}");
    }
}