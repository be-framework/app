<?php

declare(strict_types=1);

namespace Be\Skeleton;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Skeleton\Input\HelloInput;
use Be\Skeleton\Module\AppModule;
use Be\Framework\BecomingInterface;
use Koriym\SemanticLogger\SemanticLoggerInterface;
use Ray\Di\Injector;
use function dirname;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_UNICODE;
use const JSON_UNESCAPED_SLASHES;

$injector = new Injector(new AppModule());
$becoming = $injector->getInstance(BecomingInterface::class);

$becoming(new HelloInput('World'));

$logger = $injector->getInstance(SemanticLoggerInterface::class);

echo json_encode(
    $logger,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
) . PHP_EOL;
