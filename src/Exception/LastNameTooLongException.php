<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for last name that is too long
 */
#[Message([
    'en' => 'Last name "{lastName}" is too long (maximum 50 characters)',
    'ja' => '姓"{lastName}"が長すぎます（最大50文字）'
])]
final class LastNameTooLongException extends DomainException
{
    public function __construct(
        public readonly string $lastName
    ) {
        parent::__construct("Last name \"{$lastName}\" is too long (maximum 50 characters)");
    }
}