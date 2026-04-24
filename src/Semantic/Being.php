<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\Framework\Attribute\Validate;

/**
 * Being
 *
 * `$being` is a Be Framework convention used for type-matching in branching
 * metamorphosis. It is not an app-specific semantic variable, but the framework
 * still looks up a semantic validator for it in the app's Semantic namespace.
 *
 * This default no-op validator exists to suppress the framework notice:
 *   "Semantic variable 'Being' not registered in ontology namespace".
 *
 * You may edit this class to add constraints on what types can be assigned to
 * `$being` (for example, restricting it to a specific set of class names).
 */
final class Being
{
    #[Validate]
    public function validate(mixed $being): void
    {
        // No-op by default. Add constraints here if needed.
    }
}
