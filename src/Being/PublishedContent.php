<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\App\Semantic\Title;
use Be\App\Semantic\Body;
use Be\App\Semantic\Email;
use Be\App\Semantic\Category;
use Ray\InputQuery\Attribute\Input;

final readonly class PublishedContent
{
    public string $status;
    public string $publicUrl;
    public string $publishedAt;

    public function __construct(
        #[Input] public string $title,
        #[Input] public string $body,
        #[Input] public string $email,
        #[Input] public string $category,
        #[Input] public array $tags
    ) {
        $this->status = 'published';
        $this->publicUrl = '/content/' . urlencode(strtolower(str_replace(' ', '-', $this->title)));
        $this->publishedAt = date('Y-m-d H:i:s');
    }
}