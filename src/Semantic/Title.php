<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\EmptyTitleException;
use Be\App\Exception\TitleTooShortException;
use Be\App\Exception\TitleTooLongException;
use Be\App\Exception\TitleInvalidCharactersException;
use Be\Framework\Attribute\Validate;

final class Title
{
    #[Validate] 
    public function validate(string $title): void
    {
        $trimmed = trim($title);
        
        if (empty($trimmed)) {
            throw new EmptyTitleException($title);
        }
        
        if (strlen($trimmed) < 3) {
            throw new TitleTooShortException($title);
        }
        
        if (strlen($trimmed) > 200) {
            throw new TitleTooLongException($title);
        }
        
        if (preg_match('/[<>"\'\&]/', $trimmed)) {
            throw new TitleInvalidCharactersException($title);
        }
    }
}