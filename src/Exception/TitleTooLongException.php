<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for title that is too long
 */
#[Message([
    'en' => 'Title "{title}" is too long (maximum 200 characters)',
    'ja' => 'タイトル"{title}"が長すぎます（最大200文字）'
])]
final class TitleTooLongException extends DomainException
{
    public function __construct(
        public readonly string $title
    ) {
        parent::__construct("Title \"{$title}\" is too long (maximum 200 characters)");
    }
}