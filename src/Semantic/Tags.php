<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidTagsException;
use Be\Framework\Attribute\Validate;

final class Tags
{
    #[Validate] 
    public function validate(array $tags): void
    {
        if (count($tags) > 10) {
            throw new InvalidTagsException('too many tags (max 10 allowed)');
        }
        
        foreach ($tags as $tag) {
            if (!is_string($tag)) {
                throw new InvalidTagsException('all tags must be strings');
            }
            
            if (empty(trim($tag))) {
                throw new InvalidTagsException('empty tags not allowed');
            }
            
            if (strlen($tag) > 50) {
                throw new InvalidTagsException('tag too long (max 50 characters)');
            }
        }
    }
}