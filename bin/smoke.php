<?php

declare(strict_types=1);

namespace Be\Skeleton;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Skeleton\Input\HelloInput;
use Be\Skeleton\Module\DevModule;
use Be\Framework\BecomingInterface;
use Ray\Di\Injector;
use function dirname;

$injector = new Injector(new DevModule());
$becoming = $injector->getInstance(BecomingInterface::class);

$becoming(new HelloInput('World'));
