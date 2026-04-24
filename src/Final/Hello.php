<?php

declare(strict_types=1);

namespace Be\App\Final;

use Be\App\Reason\Greeting;
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
