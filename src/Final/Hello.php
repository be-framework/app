<?php

declare(strict_types=1);

namespace Be\Skeleton\Final;

use Be\Skeleton\Reason\Greeting;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

final readonly class Hello
{
    public string $greeting;

    public function __construct(
        #[Input] string $name,
        #[Inject] Greeting $greeting,
    ) {
        $this->greeting = "{$greeting->greeting} {$name}";
    }
}
