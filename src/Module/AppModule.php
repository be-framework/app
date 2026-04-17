<?php

declare(strict_types=1);

namespace Be\Skeleton\Module;

use Be\Framework\Module\BeModule;
use Be\Skeleton\Reason\Greeting;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new BeModule('Be\\Skeleton\\Semantic'));
        $this->bind(Greeting::class);
    }
}
