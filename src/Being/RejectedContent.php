<?php

declare(strict_types=1);

namespace Be\App\Being;

use Ray\InputQuery\Attribute\Input;

final readonly class RejectedContent
{
    public string $status;
    public string $rejectionReason;
    public string $rejectedAt;
    public array $rejectionDecision;

    public function __construct(
        #[Input] public string $title,
        #[Input] public string $body,
        #[Input] public string $email,
        #[Input] public string $category,
        #[Input] public array $tags,
        #[Input] public array $decision
    ) {
        $this->status = 'rejected';
        $this->rejectionReason = $decision['reason'] ?? 'Content rejected';
        $this->rejectedAt = date('Y-m-d H:i:s');
        $this->rejectionDecision = $decision;
    }
}