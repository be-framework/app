<?php

declare(strict_types=1);

namespace Be\Skeleton\Input;

use Be\Skeleton\Final\Hello;
use Be\Framework\Attribute\Be;

/** Input for Hello */
#[Be([Hello::class])]
final readonly class HelloInput
{
    public function __construct(
        public string $name
    ) {
    }
}
