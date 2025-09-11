<?php

declare(strict_types=1);

namespace Be\App\Module;

use Ray\Di\AbstractModule;
use Be\App\Reason\ContentWorkflowDecision;
use Be\App\Reason\ContentWorkflowDecision\PublicationDecision;
use Be\App\Reason\ContentWorkflowDecision\ContentQualityAssessment;
use Be\App\Reason\ContentWorkflowDecision\SecurityPolicyEnforcement;
use Be\App\Reason\ContentWorkflowDecision\UserRoleAuthorization;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Configure Reason services for dependency injection
        $this->bind(PublicationDecision::class);
        $this->bind(ContentQualityAssessment::class);
        $this->bind(SecurityPolicyEnforcement::class);
        $this->bind(UserRoleAuthorization::class);
        $this->bind(ContentWorkflowDecision::class);
    }
}
