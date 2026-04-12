<?php

declare(strict_types=1);

namespace Be\Skeleton;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Skeleton\Input\HelloInput;
use Be\Skeleton\Module\AppModule;
use Be\Framework\Becoming;
use Be\Framework\Exception\SemanticVariableException;
use Ray\Di\Injector;
use function dirname;

$injector = new Injector(new AppModule());
$becoming = new Becoming($injector, __NAMESPACE__ . '\\Semantic');

$input = new HelloInput('World');
try {
    $hello = $becoming($input);
    assert($hello instanceof Final\Hello);
    echo $hello->greeting . PHP_EOL;
} catch (SemanticVariableException $e) {
    $errorMessage = $e->getErrors()->getMessages('ja')[0];
    echo $errorMessage . PHP_EOL;
}
