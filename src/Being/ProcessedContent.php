<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\App\Reason\PublicationDecision;
use Be\Framework\Attribute\Be;
use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;

#[Be([PublishedContent::class, DraftContent::class])]
final readonly class ProcessedContent
{
    public PublishedContent|DraftContent $being;

    public function __construct(
        #[Input] public string $title,
        #[Input] public string $body,
        #[Input] public string $email,
        #[Input] public string $category,
        #[Input] public array $tags,
        #[Input] public ?string $publishDate,
        #[Inject] PublicationDecision $decision
    ) {
        $this->being = $decision->shouldPublish($this->publishDate)
            ? new PublishedContent($this->title, $this->body, $this->email, $this->category, $this->tags)
            : new DraftContent($this->title, $this->body, $this->email, $this->category, $this->tags);
    }
}