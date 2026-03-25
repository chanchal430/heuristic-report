<?php

namespace App\Services;

class ReportBuilderService
{
    /**
     * Process raw evaluation payload into a structured report.
     *
     * @param array $payload
     * @return array
     */
    public function buildFromPayload(array $payload): array
    {
        $findings = $payload['findings'] ?? [];
        
        $totalIssues = count($findings);
        $severityCounts = [
            'critical' => 0,
            'high' => 0,
            'medium' => 0,
            'low' => 0,
        ];

        // Aggregate stats & validate
        $structuredFindings = [];
        $totalSeverityWeight = 0;

        foreach ($findings as $idx => $finding) {
            $severity = strtolower($finding['severity'] ?? 'low');
            if (array_key_exists($severity, $severityCounts)) {
                $severityCounts[$severity]++;
            }

            // Normalizing structure
            $structuredFindings[] = [
                'id' => $finding['id'] ?? 'FND-' . ($idx + 1),
                'title' => $finding['title'] ?? 'Untitled Issue',
                'severity' => $severity,
                'description' => $finding['description'] ?? 'No description provided.',
                'evidence' => $finding['evidence'] ?? null,
                'recommendation' => $finding['recommendation'] ?? 'No recommendation provided.',
                'heuristic_id' => $finding['heuristic_id'] ?? 'H1',
                'heuristic_name' => $this->getHeuristicName($finding['heuristic_id'] ?? 'H1'),
            ];

            // Calculate a dummy severity weight for usability score
            $weight = match($severity) {
                'critical' => 10,
                'high' => 5,
                'medium' => 2,
                'low' => 1,
                default => 1,
            };
            $totalSeverityWeight += $weight;
        }

        // Dummy formula: 100 - (total weight of issues / total checks typical) * 100
        // We'll just invent a simple score logic for prototype:
        $baseScore = 100;
        $usabilityScore = max(0, $baseScore - ($totalSeverityWeight * 0.8));

        return [
            'metadata' => [
                'project_name' => $payload['project_name'] ?? 'Unknown Project',
                'evaluated_by' => $payload['evaluated_by'] ?? 'System',
                'date' => date('Y-m-d'),
                'version' => $payload['version'] ?? '1.0',
            ],
            'summary' => [
                'usability_score' => round($usabilityScore, 1),
                'total_issues' => $totalIssues,
                'severity_distribution' => $severityCounts,
            ],
            'findings' => $structuredFindings,
        ];
    }

    private function getHeuristicName(string $id): string
    {
        $heuristics = [
            'H1' => 'Visibility of system status',
            'H2' => 'Match between system and the real world',
            'H3' => 'User control and freedom',
            'H4' => 'Consistency and standards',
            'H5' => 'Error prevention',
            'H6' => 'Recognition rather than recall',
            'H7' => 'Flexibility and efficiency of use',
            'H8' => 'Aesthetic and minimalist design',
            'H9' => 'Help users recognize, diagnose, and recover from errors',
            'H10' => 'Help and documentation'
        ];

        return $heuristics[$id] ?? 'Unknown Heuristic';
    }
}
