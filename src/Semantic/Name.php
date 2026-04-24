<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\EmptyNameException;
use Be\Framework\Attribute\Validate;

use function trim;

/**
 * Name
 *
 * @link https://schema.org/name name property
 */
final class Name
{
    #[Validate]
    public function validate(string $name): void
    {
        if (empty(trim($name))) {
            throw new EmptyNameException();
        }
    }
}
