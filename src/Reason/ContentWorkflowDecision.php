<?php

declare(strict_types=1);

namespace Be\App\Reason;

use Be\App\Reason\ContentWorkflowDecision\PublicationDecision;
use Be\App\Reason\ContentWorkflowDecision\ContentQualityAssessment;
use Be\App\Reason\ContentWorkflowDecision\SecurityPolicyEnforcement;
use Be\App\Reason\ContentWorkflowDecision\UserRoleAuthorization;

/**
 * Content workflow decision ontology - orchestrates publication workflow
 * Integrates multiple business rules for content processing
 */
final readonly class ContentWorkflowDecision
{
    public function __construct(
        private PublicationDecision $publicationDecision,
        private ContentQualityAssessment $qualityAssessment,
        private SecurityPolicyEnforcement $securityPolicy,
        private UserRoleAuthorization $roleAuthorization
    ) {}

    public function determineWorkflowAction(
        string $title,
        string $body,
        string $email,
        string $category,
        array $tags,
        ?string $publishDate,
        string $userRole
    ): array {
        // Security check first - highest priority
        $securityAssessment = $this->securityPolicy->evaluateContentSecurity(
            $title, $body, $email, $tags
        );

        if ($this->securityPolicy->shouldBlockContent($securityAssessment)) {
            return [
                'action' => 'blocked',
                'reason' => 'Content blocked due to security violations',
                'security_assessment' => $securityAssessment,
                'next_steps' => ['Contact administrator', 'Review security policies']
            ];
        }

        // Quality assessment
        $qualityAssessment = $this->qualityAssessment->assessContentQuality(
            $title, $body, $category, $tags
        );

        // Authorization check
        $canPublish = $this->roleAuthorization->canUserPerformAction($userRole, 'publish');
        $canCreateDraft = $this->roleAuthorization->canUserPerformAction($userRole, 'create_draft');

        // Publication timing check
        $shouldPublishNow = $this->publicationDecision->shouldPublish($publishDate);

        // Decision matrix
        if ($this->securityPolicy->requiresManualReview($securityAssessment)) {
            return [
                'action' => 'pending_security_review',
                'reason' => 'Content requires manual security review',
                'security_assessment' => $securityAssessment,
                'quality_assessment' => $qualityAssessment,
                'next_steps' => ['Security team review', 'Author notification']
            ];
        }

        if (!$this->qualityAssessment->isContentReadyForPublication($qualityAssessment)) {
            if ($canCreateDraft) {
                return [
                    'action' => 'save_as_draft',
                    'reason' => 'Content quality below publication threshold',
                    'quality_assessment' => $qualityAssessment,
                    'next_steps' => ['Improve content quality', 'Address recommendations']
                ];
            }

            return [
                'action' => 'rejected',
                'reason' => 'Insufficient content quality and no draft permissions',
                'quality_assessment' => $qualityAssessment,
                'next_steps' => ['Improve content', 'Request contributor access']
            ];
        }

        if (!$canPublish) {
            if ($canCreateDraft) {
                return [
                    'action' => 'save_as_draft',
                    'reason' => 'User lacks publication privileges',
                    'authorization_reason' => $this->roleAuthorization->getAuthorizationReason($userRole, 'publish', false),
                    'next_steps' => ['Submit for editor review', 'Request higher privileges']
                ];
            }

            return [
                'action' => 'rejected',
                'reason' => 'Insufficient privileges for content submission',
                'authorization_reason' => $this->roleAuthorization->getAuthorizationReason($userRole, 'publish', false),
                'next_steps' => ['Contact administrator', 'Request access']
            ];
        }

        if (!$shouldPublishNow) {
            return [
                'action' => 'scheduled',
                'reason' => 'Content scheduled for future publication',
                'publication_reason' => $this->publicationDecision->getPublicationReason(false),
                'scheduled_date' => $publishDate,
                'next_steps' => ['Automatic publication at scheduled time']
            ];
        }

        return [
            'action' => 'publish_immediately',
            'reason' => 'All criteria met for immediate publication',
            'quality_assessment' => $qualityAssessment,
            'security_assessment' => $securityAssessment,
            'publication_reason' => $this->publicationDecision->getPublicationReason(true),
            'next_steps' => ['Content goes live', 'Notify stakeholders']
        ];
    }

    public function getWorkflowSummary(array $decision): string
    {
        $action = $decision['action'];
        $reason = $decision['reason'];

        $summary = "Content workflow decision: {$action}\nReason: {$reason}\n";

        if (isset($decision['next_steps'])) {
            $summary .= "Next steps: " . implode(', ', $decision['next_steps']) . "\n";
        }

        if (isset($decision['quality_assessment'])) {
            $qa = $decision['quality_assessment'];
            $summary .= "Quality score: {$qa['score']}/100 ({$qa['grade']})\n";
        }

        if (isset($decision['security_assessment'])) {
            $sa = $decision['security_assessment'];
            $summary .= "Security risk: {$sa['risk_level']} ({$sa['risk_score']}/100)\n";
        }

        return $summary;
    }
}