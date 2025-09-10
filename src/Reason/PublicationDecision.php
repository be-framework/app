<?php

declare(strict_types=1);

namespace Be\App\Reason;

/**
 * Publication decision ontology - determines content publication readiness
 * Based on temporal and quality criteria
 */
final readonly class PublicationDecision
{
    public function shouldPublish(?string $publishDate): bool
    {
        if ($publishDate === null) {
            return false;
        }

        $targetDate = strtotime($publishDate);
        if ($targetDate === false) {
            return false;
        }

        return $targetDate <= time();
    }

    public function getPublicationReason(bool $shouldPublish): string
    {
        return $shouldPublish 
            ? 'Content meets publication criteria - scheduled date reached'
            : 'Content held as draft - scheduled for future publication';
    }
}