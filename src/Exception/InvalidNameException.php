<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid name provided.',
    'ja' => '無効な名前が提供されました。'
])]
final class InvalidNameException extends DomainException
{
}