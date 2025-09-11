<?php

declare(strict_types=1);

namespace Be\App\Being;

use Ray\InputQuery\Attribute\Input;

final readonly class PendingReviewContent
{
    public string $status;
    public string $reviewUrl;
    public string $createdAt;
    public array $reviewDecision;

    public function __construct(
        #[Input] public string $title,
        #[Input] public string $body,
        #[Input] public string $email,
        #[Input] public string $category,
        #[Input] public array $tags,
        #[Input] public array $decision
    ) {
        $this->status = 'pending_review';
        $this->reviewUrl = '/admin/review/' . urlencode(strtolower(str_replace(' ', '-', $this->title)));
        $this->createdAt = date('Y-m-d H:i:s');
        $this->reviewDecision = $decision;
    }
}