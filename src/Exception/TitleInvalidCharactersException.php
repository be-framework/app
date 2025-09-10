<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for title with invalid characters
 */
#[Message([
    'en' => 'Title "{title}" contains invalid characters',
    'ja' => 'タイトル"{title}"に無効な文字が含まれています'
])]
final class TitleInvalidCharactersException extends DomainException
{
    public function __construct(
        public readonly string $title
    ) {
        parent::__construct("Title \"{$title}\" contains invalid characters");
    }
}