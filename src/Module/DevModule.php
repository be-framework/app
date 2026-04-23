<?php

declare(strict_types=1);

namespace Be\App\Module;

use Be\Framework\Becoming;
use Be\Framework\BecomingInterface;
use Be\App\Becoming\DevBecoming;
use Koriym\SemanticLogger\SemanticLoggerInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

final class DevModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new AppModule());
        $this->bind(Becoming::class);
        $this->bind(BecomingInterface::class)->to(DevBecoming::class);
        $this->bind(SemanticLoggerInterface::class)
            ->toProvider(DevSemanticLoggerProvider::class)
            ->in(Scope::SINGLETON);
    }
}
