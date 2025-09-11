<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\App\Reason\ContentWorkflowDecision;
use Be\Framework\Attribute\Be;
use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;

#[Be([PublishedContent::class, DraftContent::class, PendingReviewContent::class, RejectedContent::class])]
final readonly class ProcessedContent
{
    public PublishedContent|DraftContent|PendingReviewContent|RejectedContent $being;

    public function __construct(
        #[Input] public string $title,
        #[Input] public string $body,
        #[Input] public string $email,
        #[Input] public string $category,
        #[Input] public array $tags,
        #[Input] public ?string $publishDate,
        #[Input] public string $userRole,
        #[Inject] ContentWorkflowDecision $workflowDecision
    ) {
        error_log("ProcessedContent constructor called with userRole: {$userRole}");
        $decision = $workflowDecision->determineWorkflowAction(
            $this->title,
            $this->body,
            $this->email,
            $this->category,
            $this->tags,
            $this->publishDate,
            $this->userRole
        );

        error_log("Workflow decision: " . json_encode($decision));

        $this->being = match ($decision['action']) {
            'publish_immediately' => new PublishedContent($this->title, $this->body, $this->email, $this->category, $this->tags),
            'save_as_draft', 'scheduled' => new DraftContent($this->title, $this->body, $this->email, $this->category, $this->tags),
            'pending_security_review' => new PendingReviewContent($this->title, $this->body, $this->email, $this->category, $this->tags, $decision),
            'blocked', 'rejected' => new RejectedContent($this->title, $this->body, $this->email, $this->category, $this->tags, $decision)
        };

        error_log("Created being of type: " . get_class($this->being));
    }
}