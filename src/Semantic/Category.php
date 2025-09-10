<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidCategoryException;
use Be\Framework\Attribute\Validate;

final class Category
{
    private const ALLOWED_CATEGORIES = [
        'technology', 'business', 'lifestyle', 'health', 
        'education', 'entertainment', 'sports', 'politics',
        'science', 'travel', 'food', 'art'
    ];

    #[Validate] 
    public function validate(string $category): void
    {
        $normalized = strtolower(trim($category));
        
        if (empty($normalized)) {
            throw new InvalidCategoryException('', 'cannot be empty');
        }
        
        if (!in_array($normalized, self::ALLOWED_CATEGORIES, true)) {
            throw new InvalidCategoryException($normalized, 'not allowed (allowed: ' . implode(', ', self::ALLOWED_CATEGORIES) . ')');
        }
    }
}