<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\Framework\Attribute\Be;
use Be\App\Final\FormalGreeting;
use Be\App\Final\CasualGreeting;
use Be\App\Reason\CasualStyle;
use Be\App\Reason\FormalStyle;
use Be\App\Tag\English;
use Ray\InputQuery\Attribute\Input;

#[Be([FormalGreeting::class, CasualGreeting::class])]
final readonly class BeGreeting
{
    public CasualStyle|FormalStyle $being;

    public function __construct(
        #[Input] #[English] public string $name,
        #[Input] string $style
    ) {
        $this->being = $style == 'formal' ? new FormalStyle() : new CasualStyle();
    }
}
