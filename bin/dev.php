<?php

/**
 * Development entry point.
 *
 * Runs the pipeline through DevModule, which rebinds BecomingInterface to
 * DevBecoming. Every run writes a semantic log to var/log/<timestamp>.json
 * that `vendor/bin/stree` (or `composer stree`) can render as a tree.
 *
 * Output matches bin/app.php — this is a drop-in replacement with dev
 * instrumentation on top, not a separate test harness.
 */

declare(strict_types=1);

namespace Be\Skeleton;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Framework\BecomingInterface;
use Be\Framework\Exception\SemanticVariableException;
use Be\Skeleton\Input\HelloInput;
use Be\Skeleton\Module\DevModule;
use Ray\Di\Injector;

use function assert;
use function dirname;

use const PHP_EOL;

$injector = new Injector(new DevModule());
$becoming = $injector->getInstance(BecomingInterface::class);

$input = new HelloInput('World');
try {
    $hello = $becoming($input);
    assert($hello instanceof Final\Hello);
    echo $hello->greeting . PHP_EOL;
} catch (SemanticVariableException $e) {
    $errorMessage = $e->getErrors()->getMessages('ja')[0];
    echo $errorMessage . PHP_EOL;
}
