<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for last name that is too short
 */
#[Message([
    'en' => 'Last name "{lastName}" is too short (minimum 2 characters)',
    'ja' => '姓"{lastName}"が短すぎます（最低2文字）'
])]
final class LastNameTooShortException extends DomainException
{
    public function __construct(
        public readonly string $lastName
    ) {
        parent::__construct("Last name \"{$lastName}\" is too short (minimum 2 characters)");
    }
}