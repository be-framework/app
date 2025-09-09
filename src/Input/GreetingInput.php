<?php

declare(strict_types=1);

namespace Be\App\Input;

use Be\Framework\Attribute\Be;
use Be\App\Being\BeGreeting;

/**
 * Input entity that initiates greeting metamorphosis based on contextual factors
 *
 * @link https://schema.org/Action Action schema
 * @link https://schema.org/CommunicateAction Communication action schema
 * @see https://schema.org/agent
 * @see https://schema.org/instrument
 */
#[Be([BeGreeting::class])]
final readonly class GreetingInput
{
    public function __construct(
        public string $name,
        public string $style // 'formal' or 'casual'
    ) {
    }
}
