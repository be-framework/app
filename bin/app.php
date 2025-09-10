<?php

declare(strict_types=1);

namespace Be\App;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\App\Input\ContentCreationInput;
use Be\App\Input\UserRegistrationInput;
use Be\App\Module\AppModule;
use Be\Framework\Becoming;
use Be\Framework\Exception\SemanticVariableException;
use Ray\Di\Injector;
use function date;
use function dirname;
use function implode;
use function json_encode;
use function str_repeat;
use function time;
use const JSON_PRETTY_PRINT;
use const PHP_EOL;

$injector = new Injector(new AppModule());
$becoming = new Becoming($injector, __NAMESPACE__ . '\\Semantic');

echo "=== Be Framework Content Management System Demo ===" . PHP_EOL . PHP_EOL;

// Demo 1: Content Creation with immediate publication
echo "Demo 1: Content Creation (Immediate Publication)" . PHP_EOL;
$contentInput = new ContentCreationInput(
    title: "Be Framework: Revolutionary PHP Programming",
    body: "Be Framework introduces ontological programming where objects BECOME rather than DO. This paradigm shift from action-oriented to being-oriented programming creates more natural and intuitive code structures.",
    email: "developer@example.com",
    category: "technology",
    tags: ["php", "framework", "ontological", "programming"],
    publishDate: date('Y-m-d H:i:s', time() - 3600) // 1 hour ago
);

try {
    $result = $becoming($contentInput);
    echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (SemanticVariableException $e) {
    $errorMessages = $e->getErrors()->getMessages('en');
    echo "Validation Errors: " . implode(', ', $errorMessages) . PHP_EOL;
}

echo PHP_EOL . str_repeat('-', 50) . PHP_EOL . PHP_EOL;

// Demo 2: Content Creation as Draft (future publication)
echo "Demo 2: Content Creation (Future Publication - Draft)" . PHP_EOL;
$draftInput = new ContentCreationInput(
    title: "Advanced Metamorphosis Patterns",
    body: "Exploring complex transformation patterns in Be Framework including parallel assembly, type-driven branching, and constructor workshops.",
    email: "author@example.com",
    category: "education",
    tags: ["patterns", "metamorphosis", "architecture"],
    publishDate: date('Y-m-d H:i:s', time() + 7200) // 2 hours from now
);

try {
    $result = $becoming($draftInput);
    echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (SemanticVariableException $e) {
    $errorMessages = $e->getErrors()->getMessages('en');
    echo "Validation Errors: " . implode(', ', $errorMessages) . PHP_EOL;
}

echo PHP_EOL . str_repeat('-', 50) . PHP_EOL . PHP_EOL;

// Demo 3: User Registration
echo "Demo 3: User Registration System" . PHP_EOL;
$userInput = new UserRegistrationInput(
    email: "newuser@example.com",
    password: "StrongP@ssw0rd!",
    firstName: "John",
    lastName: "Developer",
    role: "editor"
);

try {
    $user = $becoming($userInput);
    echo "User Result: " . json_encode($user, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (SemanticVariableException $e) {
    $errorMessages = $e->getErrors()->getMessages('en');
    echo "User Validation Errors: " . implode(', ', $errorMessages) . PHP_EOL;
}

echo PHP_EOL . str_repeat('-', 50) . PHP_EOL . PHP_EOL;

// Demo 4: Semantic Validation Error Testing
echo "Demo 4: Semantic Validation Error Testing" . PHP_EOL;
$invalidContentInput = new ContentCreationInput(
    title: "", // Empty title - should trigger validation error
    body: "Short", // Too short body - should trigger validation error
    email: "invalid-email", // Invalid email format
    category: "invalidcategory", // Invalid category
    tags: ["valid", "", "toolongtagthatexceedsfiftycharacterslimitandwillfail"], // Invalid tags
    publishDate: "invalid-date" // Invalid date format
);

try {
    $result = $becoming($invalidContentInput);
    echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (SemanticVariableException $e) {
    echo "✓ Semantic Validation Errors Caught:" . PHP_EOL;
    $errorMessages = $e->getErrors()->getMessages('en');
    foreach ($errorMessages as $message) {
        echo "  - {$message}" . PHP_EOL;
    }
    echo PHP_EOL . "✓ Japanese Error Messages:" . PHP_EOL;
    $japaneseMessages = $e->getErrors()->getMessages('ja');
    foreach ($japaneseMessages as $message) {
        echo "  - {$message}" . PHP_EOL;
    }
}

echo PHP_EOL . "=== End of Demo ===" . PHP_EOL;

