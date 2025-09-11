<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\Framework\Attribute\Be;
use Ray\InputQuery\Attribute\Input;

#[Be([ProcessedContent::class])]
final readonly class ValidatedContent
{
    public function __construct(
        #[Input] public string $title,
        #[Input] public string $body,
        #[Input] public string $email,
        #[Input] public string $category,
        #[Input] public array $tags,
        #[Input] public ?string $publishDate,
        #[Input] public string $userRole = 'contributor'
    ) {}
}