<?php

declare(strict_types=1);

namespace Be\App\Input;

use Be\App\Being\UnvalidatedContent;
use Be\Framework\Attribute\Be;

#[Be([UnvalidatedContent::class])]
final readonly class ContentCreationInput
{
    public function __construct(
        public string $title,
        public string $body,
        public string $email,
        public string $category,
        public array $tags = [],
        public ?string $publishDate = null
    ) {}
}