<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidPublishDateException;
use Be\Framework\Attribute\Validate;

final class PublishDate
{
    #[Validate] 
    public function validate(?string $publishDate): void
    {
        if ($publishDate === null) {
            return; // null is valid (draft content)
        }
        
        if (empty(trim($publishDate))) {
            throw new InvalidPublishDateException('', 'cannot be empty string');
        }
        
        $timestamp = strtotime($publishDate);
        if ($timestamp === false) {
            throw new InvalidPublishDateException($publishDate, 'invalid date format');
        }
        
        // Check if date is too far in the future (1 year)
        if ($timestamp > time() + (365 * 24 * 60 * 60)) {
            throw new InvalidPublishDateException($publishDate, 'too far in the future (max 1 year ahead)');
        }
        
        // Check if date is too far in the past (10 years)
        if ($timestamp < time() - (10 * 365 * 24 * 60 * 60)) {
            throw new InvalidPublishDateException($publishDate, 'too far in the past (max 10 years ago)');
        }
    }
}