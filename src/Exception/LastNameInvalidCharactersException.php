<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for last name with invalid characters
 */
#[Message([
    'en' => 'Last name "{lastName}" contains invalid characters',
    'ja' => '姓"{lastName}"に無効な文字が含まれています'
])]
final class LastNameInvalidCharactersException extends DomainException
{
    public function __construct(
        public readonly string $lastName
    ) {
        parent::__construct("Last name \"{$lastName}\" contains invalid characters");
    }
}