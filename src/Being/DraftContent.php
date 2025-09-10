<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\App\Semantic\Title;
use Be\App\Semantic\Body;
use Be\App\Semantic\Email;
use Be\App\Semantic\Category;
use Ray\InputQuery\Attribute\Input;

final readonly class DraftContent
{
    public string $status;
    public string $draftUrl;
    public string $createdAt;

    public function __construct(
        #[Input] public string $title,
        #[Input] public string $body,
        #[Input] public string $email,
        #[Input] public string $category,
        #[Input] public array $tags
    ) {
        $this->status = 'draft';
        $this->draftUrl = '/drafts/' . urlencode(strtolower(str_replace(' ', '-', $this->title)));
        $this->createdAt = date('Y-m-d H:i:s');
    }
}