<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\App\Reason\ContentProcessor;
use Be\App\Reason\ContentStatus;
use Be\App\Semantic\Title;
use Be\App\Semantic\Body;
use Be\App\Semantic\Email;
use Be\App\Semantic\Category;
use Be\Framework\Attribute\Be;
use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;

#[Be([ProcessedContent::class])]
final readonly class ValidatedContent
{
    public function __construct(
        #[Input] public string $title,
        #[Input] public string $body,
        #[Input] public string $email,
        #[Input] public string $category,
        #[Input] public array $tags,
        #[Input] public ?string $publishDate
    ) {}
}