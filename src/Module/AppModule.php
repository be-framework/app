<?php

declare(strict_types=1);

namespace Be\App\Module;

use Be\App\Reason\Greeting;
use Be\Framework\Module\BeModule;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new BeModule('Be\\App\\Semantic'));
        $this->bind(Greeting::class);
    }
}
