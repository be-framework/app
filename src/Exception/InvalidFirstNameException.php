<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid first name
 */
#[Message([
    'en' => 'Invalid first name provided.',
    'ja' => '無効な名前が提供されました。'
])]
final class InvalidFirstNameException extends DomainException
{
}