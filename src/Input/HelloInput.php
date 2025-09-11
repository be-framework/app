<?php

declare(strict_types=1);

namespace Be\App\Input;

use Be\App\Final\Hello;
use Be\Framework\Attribute\Be;

/** Input for Hello being　*/
#[Be([Hello::class])]
final readonly class HelloInput
{
    public function __construct(
        public string $name
    ) {
    }
}
