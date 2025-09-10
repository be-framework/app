<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid user role
 */
#[Message([
    'en' => 'Invalid user role specified.',
    'ja' => '無効なユーザーロールが指定されました。'
])]
final class InvalidRoleException extends DomainException
{
}