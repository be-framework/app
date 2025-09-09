<?php

declare(strict_types=1);

namespace Be\App;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Framework\Becoming;
use Be\App\Input\GreetingInput;
use Ray\Di\Injector;
use function dirname;

// Be Framework metamorphosis demonstration
// Generates appropriate greeting Being based on GreetingInput

// Create Becoming instance with DI container and semantic namespace
$becoming = new Becoming(new Injector(), __NAMESPACE__ . '\\Semantic');

// Create casual greeting input
$input = new GreetingInput('Alice', 'casual');
// Formal greeting example (commented out)
// $input = new GreetingInput('Smith', 'formal');

// Execute metamorphosis to generate Being
$casualGreeting = $becoming($input);
echo "✅ Casual existence:\n" . json_encode($casualGreeting, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

//$input = new GreetingInput('', 'casual');
//try {
//    $becoming($input);
//} catch (SemanticVariableException $e) {
//    $exceptionType = get_class($e->getErrors()->exceptions[0]);
//    $errorMessages = $e->getErrors()->getMessages('ja');
//    echo "✅ $exceptionType: {$errorMessages[0]}\n";
//}
