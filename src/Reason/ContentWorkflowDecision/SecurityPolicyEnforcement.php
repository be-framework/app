<?php

declare(strict_types=1);

namespace Be\App\Reason\ContentWorkflowDecision;

/**
 * Security policy enforcement ontology - determines security compliance
 * Based on content safety and platform policies
 */
final readonly class SecurityPolicyEnforcement
{
    private array $forbiddenPatterns;
    private array $suspiciousPatterns;
    private array $requiredSecurityChecks;

    public function __construct()
    {
        $this->forbiddenPatterns = [
            'script_injection' => [
                '/<script[^>]*>.*?<\/script>/i',
                '/javascript:/i',
                '/on\w+\s*=/i'
            ],
            'sql_injection' => [
                '/union\s+select/i',
                '/drop\s+table/i',
                '/insert\s+into/i',
                '/delete\s+from/i'
            ],
            'harmful_content' => [
                '/\b(violence|hate|harassment)\b/i',
                '/\b(malware|virus|trojan)\b/i'
            ]
        ];

        $this->suspiciousPatterns = [
            '/\b(click\s+here|free\s+money|get\s+rich)\b/i',
            '/\b(download\s+now|limited\s+time)\b/i',
            '/\b(phishing|scam|fraud)\b/i'
        ];

        $this->requiredSecurityChecks = [
            'xss_prevention',
            'content_sanitization', 
            'spam_detection',
            'malicious_link_check'
        ];
    }

    public function evaluateContentSecurity(
        string $title,
        string $body,
        string $authorEmail,
        array $tags
    ): array {
        $violations = [];
        $warnings = [];
        $riskScore = 0;

        // Check title security
        $titleViolations = $this->checkForViolations($title, 'title');
        $violations = array_merge($violations, $titleViolations);
        $riskScore += count($titleViolations) * 20;

        // Check body security
        $bodyViolations = $this->checkForViolations($body, 'body');
        $violations = array_merge($violations, $bodyViolations);
        $riskScore += count($bodyViolations) * 15;

        // Check for suspicious patterns
        $suspiciousMatches = $this->checkSuspiciousPatterns($body);
        if (!empty($suspiciousMatches)) {
            $warnings[] = "Content contains suspicious patterns: " . implode(', ', $suspiciousMatches);
            $riskScore += count($suspiciousMatches) * 5;
        }

        // Email reputation check
        $emailRisk = $this->assessEmailReputation($authorEmail);
        if ($emailRisk > 0) {
            $warnings[] = "Author email shows elevated risk indicators";
            $riskScore += $emailRisk;
        }

        // Tag analysis
        $tagViolations = $this->checkTagSecurity($tags);
        $violations = array_merge($violations, $tagViolations);
        $riskScore += count($tagViolations) * 10;

        return [
            'is_safe' => empty($violations) && $riskScore < 30,
            'risk_score' => min(100, $riskScore),
            'risk_level' => $this->getRiskLevel($riskScore),
            'violations' => $violations,
            'warnings' => $warnings,
            'recommended_action' => $this->getRecommendedAction($riskScore, $violations)
        ];
    }

    public function shouldBlockContent(array $securityAssessment): bool
    {
        return !$securityAssessment['is_safe'] || 
               $securityAssessment['risk_score'] > 70 ||
               !empty($securityAssessment['violations']);
    }

    public function requiresManualReview(array $securityAssessment): bool
    {
        return $securityAssessment['risk_score'] > 30 || 
               count($securityAssessment['warnings']) > 2;
    }

    private function checkForViolations(string $content, string $context): array
    {
        $violations = [];

        foreach ($this->forbiddenPatterns as $category => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $violations[] = "Security violation in {$context}: {$category} detected";
                }
            }
        }

        return $violations;
    }

    private function checkSuspiciousPatterns(string $content): array
    {
        $matches = [];

        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content, $match)) {
                $matches[] = $match[0];
            }
        }

        return array_unique($matches);
    }

    private function assessEmailReputation(string $email): int
    {
        $riskScore = 0;
        $domain = substr(strrchr($email, "@"), 1);

        // Check for suspicious domains
        $suspiciousDomains = ['tempmail.com', '10minutemail.com', 'guerrillamail.com'];
        if (in_array($domain, $suspiciousDomains, true)) {
            $riskScore += 15;
        }

        // Check for suspicious email patterns
        if (preg_match('/\d{6,}/', $email)) { // Many consecutive numbers
            $riskScore += 5;
        }

        if (str_contains($email, '+')) { // Email aliasing
            $riskScore += 3;
        }

        return $riskScore;
    }

    private function checkTagSecurity(array $tags): array
    {
        $violations = [];

        foreach ($tags as $tag) {
            if (strlen($tag) > 50) {
                $violations[] = "Excessively long tag detected: " . substr($tag, 0, 30) . "...";
            }

            if (preg_match('/[<>"\']/', $tag)) {
                $violations[] = "Tag contains potentially dangerous characters: {$tag}";
            }
        }

        return $violations;
    }

    private function getRiskLevel(int $riskScore): string
    {
        return match (true) {
            $riskScore >= 70 => 'Critical',
            $riskScore >= 50 => 'High', 
            $riskScore >= 30 => 'Medium',
            $riskScore >= 10 => 'Low',
            default => 'Minimal'
        };
    }

    private function getRecommendedAction(int $riskScore, array $violations): string
    {
        if (!empty($violations)) {
            return 'Block content immediately due to security violations';
        }

        return match (true) {
            $riskScore >= 70 => 'Block content and flag for security review',
            $riskScore >= 50 => 'Hold for manual security review',
            $riskScore >= 30 => 'Review and sanitize before publication',
            $riskScore >= 10 => 'Monitor and apply content filters',
            default => 'Proceed with standard publication workflow'
        };
    }
}