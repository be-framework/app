<?php

declare(strict_types=1);

use Be\App\Input\ContentCreationInput;
use Be\App\Module\AppModule;
use Be\Framework\Becoming;
use Ray\Di\Injector;

require_once __DIR__ . '/vendor/autoload.php';

$injector = new Injector(new AppModule());
$becoming = new Becoming($injector, 'Be\\App\\Semantic');

// Simple test with contributor role
$input = new ContentCreationInput(
    title: "Test Article",
    body: "Short content.", // This should fail quality check
    email: "test@example.com",
    category: "technology",
    tags: ["test"],
    publishDate: null,
    userRole: "contributor" // Should not have publish permission
);

echo "Testing with contributor role and poor quality content..." . PHP_EOL;

try {
    $result = $becoming($input);
    echo "Result type: " . get_class($result) . PHP_EOL;
    echo "Status: " . ($result->status ?? 'no status') . PHP_EOL;
    
    if (property_exists($result, 'rejectionDecision')) {
        echo "Rejection Decision: " . json_encode($result->rejectionDecision, JSON_PRETTY_PRINT) . PHP_EOL;
    }
    
    if (property_exists($result, 'reviewDecision')) {
        echo "Review Decision: " . json_encode($result->reviewDecision, JSON_PRETTY_PRINT) . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
}