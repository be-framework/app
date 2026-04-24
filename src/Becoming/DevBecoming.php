<?php

declare(strict_types=1);

namespace Be\App\Becoming;

use Be\Framework\Becoming;
use Be\Framework\BecomingInterface;
use Koriym\SemanticLogger\DevLogger;
use Koriym\SemanticLogger\SemanticLoggerInterface;
use Override;

final class DevBecoming implements BecomingInterface
{
    public function __construct(
        private readonly Becoming $becoming,
        private readonly SemanticLoggerInterface $logger,
    ) {
    }

    #[Override]
    public function __invoke(object $input): object
    {
        try {
            return ($this->becoming)($input);
        } finally {
            // Always emit the semantic log — failed runs are exactly the ones
            // we most want to inspect via `composer stree`.
            (new DevLogger(dirname(__DIR__, 2) . '/var/log'))->log($this->logger);
        }
    }
}
