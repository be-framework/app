<?php

declare(strict_types=1);

namespace Be\Skeleton\Module;

use Be\Framework\Becoming;
use Be\Framework\BecomingInterface;
use Be\Skeleton\Becoming\DevBecoming;
use Ray\Di\AbstractModule;

final class DevModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new AppModule());
        $this->bind(Becoming::class);
        $this->bind(BecomingInterface::class)->to(DevBecoming::class);
    }
}
