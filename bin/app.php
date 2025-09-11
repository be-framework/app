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
$becoming = new Becoming($injector, 'Be\\App\\Semantic');

echo "=== Be Framework Content Management System Demo ===" . PHP_EOL . PHP_EOL;

// Demo 1: Content Creation with immediate publication (Editor Role)
echo "Demo 1: Content Creation (Immediate Publication - Editor Role)" . PHP_EOL;
$contentInput = new ContentCreationInput(
    title: "Be Framework: Revolutionary PHP Programming",
    body: "Be Framework introduces ontological programming where objects BECOME rather than DO. This paradigm shift from action-oriented to being-oriented programming creates more natural and intuitive code structures.",
    email: "developer@example.com",
    category: "technology",
    tags: ["php", "framework", "ontological", "programming"],
    publishDate: date('Y-m-d H:i:s', time() - 3600), // 1 hour ago
    userRole: "editor"
);

try {
    $result = $becoming($contentInput);
    echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (SemanticVariableException $e) {
    $errorMessages = $e->getErrors()->getMessages('en');
    echo "Validation Errors: " . implode(', ', $errorMessages) . PHP_EOL;
}

echo PHP_EOL . str_repeat('-', 50) . PHP_EOL . PHP_EOL;

// Demo 2: Content Creation with contributor role (should create draft)
echo "Demo 2: Content Creation (Contributor Role - Draft)" . PHP_EOL;
$draftInput = new ContentCreationInput(
    title: "Advanced Metamorphosis Patterns",
    body: "Exploring complex transformation patterns in Be Framework including parallel assembly, type-driven branching, and constructor workshops.",
    email: "author@example.com",
    category: "education",
    tags: ["patterns", "metamorphosis", "architecture"],
    publishDate: date('Y-m-d H:i:s', time() - 1800), // 30 minutes ago
    userRole: "contributor"
);

try {
    $result = $becoming($draftInput);
    echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (SemanticVariableException $e) {
    $errorMessages = $e->getErrors()->getMessages('en');
    echo "Validation Errors: " . implode(', ', $errorMessages) . PHP_EOL;
}

echo PHP_EOL . str_repeat('-', 50) . PHP_EOL . PHP_EOL;

// Demo 3: Content Creation with poor quality (should be rejected/draft)
echo "Demo 3: Content Creation (Poor Quality - Subscriber Role)" . PHP_EOL;
$poorQualityInput = new ContentCreationInput(
    title: "Short Title",
    body: "Very short content that lacks depth.",
    email: "subscriber@example.com",
    category: "technology",
    tags: ["test"],
    publishDate: date('Y-m-d H:i:s', time() - 1800), // 30 minutes ago
    userRole: "subscriber"
);

try {
    $result = $becoming($poorQualityInput);
    echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (Exception $e) {
    echo "Exception caught: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . str_repeat('-', 50) . PHP_EOL . PHP_EOL;

// Demo 4: User Registration
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

echo PHP_EOL . "--------------------------------------------------" . PHP_EOL;

// Demo 5: Advanced Reason Services Integration
echo "Demo 5: Advanced Reason Services Integration" . PHP_EOL;

use Be\App\Reason\ContentWorkflowDecision\UserRoleAuthorization;
use Be\App\Reason\ContentWorkflowDecision\ContentQualityAssessment;
use Be\App\Reason\ContentWorkflowDecision\SecurityPolicyEnforcement;
use Be\App\Reason\ContentWorkflowDecision;

// Initialize advanced reason services
$roleAuth = new UserRoleAuthorization();
$qualityAssessment = new ContentQualityAssessment();
$securityPolicy = new SecurityPolicyEnforcement();
$workflowDecision = new ContentWorkflowDecision(
    new \Be\App\Reason\ContentWorkflowDecision\PublicationDecision(),
    $qualityAssessment,
    $securityPolicy,
    $roleAuth
);

echo PHP_EOL . "5a. Role Authorization Analysis:" . PHP_EOL;
$testRoles = ['subscriber', 'contributor', 'editor', 'admin'];
$testActions = ['read', 'create_draft', 'publish', 'manage_users'];

foreach ($testRoles as $role) {
    echo "Role: {$role}" . PHP_EOL;
    foreach ($testActions as $action) {
        $canPerform = $roleAuth->canUserPerformAction($role, $action);
        $status = $canPerform ? '✓' : '✗';
        echo "  {$status} {$action}" . PHP_EOL;
    }
    echo PHP_EOL;
}

echo "5b. Content Quality Assessment:" . PHP_EOL;
$qualityTest = $qualityAssessment->assessContentQuality(
    "Be Framework Advanced Patterns",
    "Be Framework represents a paradigm shift in PHP development. This comprehensive guide explores advanced metamorphosis patterns, constructor-driven transformations, and ontological programming principles. Through practical examples and detailed analysis, developers will learn to implement type-driven self-determination, semantic validation systems, and sophisticated business logic encapsulation using ontological services.",
    "technology",
    ["php", "framework", "ontology", "patterns"]
);

echo "Quality Score: {$qualityTest['score']}/100 ({$qualityTest['grade']})" . PHP_EOL;
echo "Reading Time: {$qualityTest['reading_time_minutes']} minutes" . PHP_EOL;
echo "Word Count: {$qualityTest['word_count']} words" . PHP_EOL;
if (!empty($qualityTest['issues'])) {
    echo "Issues:" . PHP_EOL;
    foreach ($qualityTest['issues'] as $issue) {
        echo "  - {$issue}" . PHP_EOL;
    }
}
echo PHP_EOL;

echo "5c. Security Policy Enforcement:" . PHP_EOL;
$securityTest = $securityPolicy->evaluateContentSecurity(
    "Legitimate Article Title",
    "This is a normal article about programming concepts. It contains technical information and code examples without any malicious content.",
    "developer@example.com",
    ["programming", "tutorial"]
);

echo "Security Status: " . ($securityTest['is_safe'] ? 'Safe' : 'Unsafe') . PHP_EOL;
echo "Risk Level: {$securityTest['risk_level']} (Score: {$securityTest['risk_score']}/100)" . PHP_EOL;
echo "Recommended Action: {$securityTest['recommended_action']}" . PHP_EOL;
echo PHP_EOL;

echo "5d. Comprehensive Workflow Decision:" . PHP_EOL;
$workflowTest = $workflowDecision->determineWorkflowAction(
    "Advanced Be Framework Implementation Guide",
    "This comprehensive guide covers advanced Be Framework patterns including metamorphosis chains, ontological services, and semantic validation. Learn to build production-ready applications using constructor-driven transformations and type-driven self-determination. Includes practical examples, best practices, and performance considerations for enterprise applications.",
    "expert@example.com",
    "technology",
    ["php", "framework", "guide", "advanced"],
    date('Y-m-d H:i:s', time() - 1800), // 30 minutes ago
    "editor"
);

echo $workflowDecision->getWorkflowSummary($workflowTest) . PHP_EOL;

echo PHP_EOL . "=== End of Demo ===" . PHP_EOL;

