<?php

/**
 * Universal entry point for a Be Framework app.
 *
 * Invocation mirrors a URI: `<input>?<query>` where:
 *   - `<input>` is mapped to `Be\Skeleton\Input\<Ucfirst>Input`
 *   - `<query>` is parsed by `parse_str()` and spread as named constructor args
 *
 * The Module is selected by the `MODULE` environment variable
 * (`Be\Skeleton\Module\<Ucfirst>Module`); defaults to `dev`.
 *
 * Examples:
 *   php bin/app.php hello                              # MODULE=dev, no args
 *   php bin/app.php 'hello?name=Alice'
 *   MODULE=app php bin/app.php 'hello?name=Alice'      # production-style
 *   php bin/app.php 'order?customerId=42&items[]=P1001&items[]=P1002'
 *
 * Note: declare(strict_types=1) is intentionally omitted so query-string
 * values (always strings) can coerce to typed Input constructor params
 * (int / float / bool) under PHP's standard coercion rules.
 */

namespace Be\Skeleton;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Framework\BecomingInterface;
use Be\Framework\Exception\SemanticVariableException;
use Ray\Di\Injector;

use function array_slice;
use function dirname;
use function getenv;
use function json_encode;
use function parse_str;
use function parse_url;
use function ucfirst;

use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_UNICODE;
use const PHP_EOL;

$module = getenv('MODULE') ?: 'dev';
$invocation = array_slice($argv, 1)[0] ?? 'hello?name=World';

$parts = parse_url($invocation);
$inputName = $parts['path'] ?? 'hello';
parse_str($parts['query'] ?? '', $opts);

$moduleClass = __NAMESPACE__ . '\\Module\\' . ucfirst($module) . 'Module';
$inputClass  = __NAMESPACE__ . '\\Input\\'  . ucfirst($inputName) . 'Input';

try {
    $injector = new Injector(new $moduleClass());
    $becoming = $injector->getInstance(BecomingInterface::class);
    $final = $becoming(new $inputClass(...$opts));
    echo ($final->greeting ?? json_encode($final, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . PHP_EOL;
} catch (SemanticVariableException $e) {
    echo $e->getErrors()->getMessages('ja')[0] . PHP_EOL;
}
