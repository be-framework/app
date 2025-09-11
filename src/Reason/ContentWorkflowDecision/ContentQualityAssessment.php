<?php

declare(strict_types=1);

namespace Be\App\Reason\ContentWorkflowDecision;

/**
 * Content quality assessment ontology - determines content readiness
 * Based on editorial standards and quality metrics
 */
final readonly class ContentQualityAssessment
{
    private array $qualityRules;
    private array $categoryRequirements;

    public function __construct()
    {
        $this->qualityRules = [
            'min_title_length' => 10,
            'max_title_length' => 150,
            'min_body_length' => 100,
            'min_paragraph_count' => 2,
            'max_tag_count' => 8,
            'min_reading_time_minutes' => 1
        ];

        $this->categoryRequirements = [
            'technology' => ['technical_accuracy', 'code_examples'],
            'business' => ['market_analysis', 'practical_value'],
            'education' => ['learning_objectives', 'structured_content'],
            'health' => ['medical_disclaimer', 'source_citations'],
            'science' => ['peer_review', 'methodology']
        ];
    }

    public function assessContentQuality(
        string $title,
        string $body,
        string $category,
        array $tags
    ): array {
        $score = 100;
        $issues = [];
        $recommendations = [];

        // Title assessment
        $titleLength = strlen(trim($title));
        if ($titleLength < $this->qualityRules['min_title_length']) {
            $score -= 15;
            $issues[] = "Title too short ({$titleLength} chars, minimum {$this->qualityRules['min_title_length']})";
            $recommendations[] = "Expand title to be more descriptive";
        }

        // Body assessment
        $bodyLength = strlen(trim($body));
        $wordCount = str_word_count($body);
        $paragraphCount = substr_count($body, "\n\n") + 1;

        if ($bodyLength < $this->qualityRules['min_body_length']) {
            $score -= 25;
            $issues[] = "Content too short ({$bodyLength} chars, minimum {$this->qualityRules['min_body_length']})";
            $recommendations[] = "Add more detailed content and examples";
        }

        if ($paragraphCount < $this->qualityRules['min_paragraph_count']) {
            $score -= 10;
            $issues[] = "Insufficient paragraph structure";
            $recommendations[] = "Break content into more readable paragraphs";
        }

        // Tag assessment
        if (count($tags) > $this->qualityRules['max_tag_count']) {
            $score -= 5;
            $issues[] = "Too many tags (" . count($tags) . ", maximum {$this->qualityRules['max_tag_count']})";
            $recommendations[] = "Focus on the most relevant tags";
        }

        // Reading time assessment
        $readingTimeMinutes = ceil($wordCount / 200); // Average reading speed
        if ($readingTimeMinutes < $this->qualityRules['min_reading_time_minutes']) {
            $score -= 10;
            $issues[] = "Content may be too brief for meaningful engagement";
            $recommendations[] = "Consider adding more depth and examples";
        }

        // Category-specific requirements
        if (isset($this->categoryRequirements[$category])) {
            $missing = $this->checkCategoryRequirements($body, $category);
            if (!empty($missing)) {
                $score -= count($missing) * 5;
                $issues = array_merge($issues, $missing);
            }
        }

        return [
            'score' => max(0, $score),
            'grade' => $this->getQualityGrade($score),
            'issues' => $issues,
            'recommendations' => $recommendations,
            'reading_time_minutes' => $readingTimeMinutes,
            'word_count' => $wordCount
        ];
    }

    public function isContentReadyForPublication(array $assessment): bool
    {
        return $assessment['score'] >= 70 && count($assessment['issues']) <= 2;
    }

    public function getQualityGrade(int $score): string
    {
        return match (true) {
            $score >= 90 => 'Excellent',
            $score >= 80 => 'Good',
            $score >= 70 => 'Acceptable',
            $score >= 60 => 'Needs Improvement',
            default => 'Poor'
        };
    }

    private function checkCategoryRequirements(string $body, string $category): array
    {
        $missing = [];
        $requirements = $this->categoryRequirements[$category] ?? [];

        foreach ($requirements as $requirement) {
            if (!$this->hasRequirement($body, $requirement)) {
                $missing[] = "Missing {$requirement} for {$category} content";
            }
        }

        return $missing;
    }

    private function hasRequirement(string $body, string $requirement): bool
    {
        // Simplified requirement checking
        return match ($requirement) {
            'technical_accuracy' => str_contains(strtolower($body), 'technical') || str_contains($body, '```'),
            'code_examples' => str_contains($body, '```') || str_contains($body, 'function'),
            'market_analysis' => str_contains(strtolower($body), 'market') || str_contains(strtolower($body), 'business'),
            'practical_value' => str_contains(strtolower($body), 'practical') || str_contains(strtolower($body), 'example'),
            'learning_objectives' => str_contains(strtolower($body), 'learn') || str_contains(strtolower($body), 'understand'),
            'structured_content' => substr_count($body, "\n\n") >= 2,
            'medical_disclaimer' => str_contains(strtolower($body), 'disclaimer') || str_contains(strtolower($body), 'consult'),
            'source_citations' => str_contains($body, 'http') || str_contains($body, 'source'),
            'peer_review' => str_contains(strtolower($body), 'study') || str_contains(strtolower($body), 'research'),
            'methodology' => str_contains(strtolower($body), 'method') || str_contains(strtolower($body), 'approach'),
            default => true
        };
    }
}