<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for empty title
 */
#[Message([
    'en' => 'Title "{title}" cannot be empty',
    'ja' => 'タイトル"{title}"は空にできません'
])]
final class EmptyTitleException extends DomainException
{
    public function __construct(
        public readonly string $title
    ) {
        parent::__construct("Title \"{$title}\" cannot be empty");
    }
}