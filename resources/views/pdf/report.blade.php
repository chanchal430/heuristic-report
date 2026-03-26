<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Heuristic Evaluation Report</title>

    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.5;
            font-size: 13px;
        }

        /* ================= HEADER ================= */

        .report-header {
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header-title {
            font-size: 26px;
            font-weight: bold;
        }

        .header-meta {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        /* ================= SUMMARY CARDS ================= */

        .summary-grid {
            text-align: center;
            margin-bottom: 25px;
        }

        .stat-card {
            display: inline-block;
            width: 30%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-right: 2%;
        }

        .stat-card:last-child {
            margin-right: 0;
        }

        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
        }

        .stat-value {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
        }

        .text-critical {
            color: #ef4444;
        }

        /* ================= SECTION HEADER ================= */

        .section-header {
            font-size: 16px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 4px;
        }

        /* ================= EXEC SUMMARY LAYOUT ================= */

        .exec-wrapper {
            width: 100%;
        }

        .exec-left {
            width: 60%;
            display: inline-block;
            vertical-align: top;
        }

        .exec-right {
            width: 38%;
            display: inline-block;
            vertical-align: top;
            margin-left: 2%;
        }

        /* distribution */

        .distribution-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 6px;
        }

        .dist-row {
            margin-bottom: 8px;
        }

        .dist-label {
            display: inline-block;
            width: 70px;
            font-size: 11px;
            font-weight: bold;
        }

        .dist-bar-bg {
            display: inline-block;
            width: 180px;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
        }

        .dist-bar {
            height: 8px;
            border-radius: 4px;
        }

        .bg-critical {
            background: #ef4444
        }

        .bg-high {
            background: #f97316
        }

        .bg-medium {
            background: #eab308
        }

        .bg-low {
            background: #94a3b8
        }

        /* ================= KEY TAKEAWAYS ================= */

        .key-box {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 12px;
            border-radius: 4px;
        }

        .key-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .key-item {
            font-size: 12px;
            margin-bottom: 4px;
        }

        /* ================= FINDING CARD ================= */

        .finding-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .severity-badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            color: white;
            border-radius: 4px;
            margin-right: 6px;
        }

        .heuristic-tag {
            font-size: 10px;
            color: #64748b;
        }

        .finding-title {
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0;
        }

        .finding-section {
            margin-bottom: 8px;
        }

        .finding-section-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
        }

        .recommendation {
            background: #f0fdf4;
            padding: 8px;
            border-radius: 4px;
        }

        /* ================= TABLE ================= */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f5f9;
            text-align: left;
            padding: 6px;
            font-size: 11px;
        }

        td {
            padding: 6px;
            font-size: 11px;
            border-bottom: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>

    <!-- ================= HEADER ================= -->

    <div id="section-metadata" class="report-header">
        <div class="header-title">
            {{ $report['metadata']['project_name'] }}
        </div>

        <div class="header-meta">
            Heuristic Evaluation v{{ $report['metadata']['version'] }}
            |
            Evaluated by {{ $report['metadata']['evaluated_by'] }}
            |
            {{ $report['metadata']['date'] }}
        </div>
    </div>
    
    <!-- ================= TABLE OF CONTENTS ================= -->
    <div style="margin: 40px 0; padding: 25px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <h2 style="font-size: 20px; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; display: inline-block;">
            Report Structure
        </h2>
        <div style="margin-top: 10px;">
            @foreach($report['sections'] as $section)
                <div style="margin-bottom: 12px; border-bottom: 1px dotted #cbd5e1; padding-bottom: 4px;">
                    <a href="#section-{{ $section['id'] }}" style="text-decoration: none; color: #1e293b; font-size: 14px; display: block;">
                        <span style="color: #2563eb; font-weight: bold; margin-right: 10px;">0{{ $loop->iteration }}</span>
                        <span style="font-weight: 500;">{{ $section['label'] }}</span>
                        <span style="float: right; color: #64748b; font-size: 11px;">→</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div style="page-break-after: always;"></div>


    <!-- ================= SUMMARY ================= -->

    <div id="section-overview" class="summary-grid">

        <div class="stat-card">
            <div class="stat-label">Usability Score</div>
            <div class="stat-value">
                {{ $report['summary']['usability_score'] }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total Issues</div>
            <div class="stat-value">
                {{ $report['summary']['total_issues'] }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Critical Issues</div>
            <div class="stat-value text-critical">
                {{ $report['summary']['severity_distribution']['critical'] ?? 0 }}
            </div>
        </div>

    </div>


    <!-- ================= EXEC SUMMARY ================= -->

    <div id="section-summary" class="section-header">Executive Summary</div>

    <table width="100%">
        <tr>

            <!-- LEFT -->
            <td width="50%" valign="top">

                <div class="distribution-box">

                    @foreach($report['summary']['severity_distribution'] as $severity => $count)

                    @php
                    $total = $report['summary']['total_issues'] ?: 1;
                    $percent = ($count / $total) * 100;
                    @endphp

                    <div class="dist-row">
                        <span class="dist-label">{{ ucfirst($severity) }}</span>

                        <div class="dist-bar-bg">
                            <div class="dist-bar bg-{{ strtolower($severity) }}"
                                style="width: {{ max(5, (int)$percent) }}%;">
                            </div>
                        </div>

                        <span style="font-size:11px;font-weight:bold;margin-left:5px;">
                            {{ $count }}
                        </span>

                    </div>

                    @endforeach

                </div>

            </td>

            <!-- RIGHT -->
            <td width="50%" valign="top">

                <div class="key-box">

                    <div class="key-title">Key Takeaways</div>

                    <div class="key-item">
                        • {{ $report['summary']['total_issues'] }} usability issues identified
                    </div>

                    <div class="key-item">
                        • {{ $report['summary']['severity_distribution']['critical'] ?? 0 }}
                        critical issues require immediate attention
                    </div>

                    <div class="key-item">
                        • Focus on high-impact user flows first
                    </div>

                    <div class="key-item">
                        • Improve consistency across UI patterns
                    </div>

                </div>

            </td>

        </tr>
    </table>


    <!-- ================= FINDINGS ================= -->

    <div id="section-findings" class="section-header">Detailed Findings</div>

    @foreach($report['findings'] as $finding)

    <div class="finding-card">

        <span class="severity-badge bg-{{ strtolower($finding['severity']) }}">
            {{ strtoupper($finding['severity']) }}
        </span>

        <span class="heuristic-tag">
            {{ $finding['heuristic_id'] }} — {{ $finding['heuristic_name'] }}
        </span>

        <div class="finding-title">
            {{ $finding['title'] }}
        </div>

        <div class="finding-section">
            <div class="finding-section-title">Description</div>
            {{ $finding['description'] }}
        </div>

        @if($finding['evidence'])
        <div class="finding-section">
            <div class="finding-section-title">Evidence</div>
            {{ $finding['evidence'] }}
        </div>
        @endif

        <div class="finding-section">
            <div class="finding-section-title">Recommendation</div>
            <div class="recommendation">
                {{ $finding['recommendation'] }}
            </div>
        </div>

    </div>

    @endforeach


    <!-- ================= EVALUATOR INSIGHTS ================= -->
    <div id="section-insights" class="section-header">Evaluator Insights</div>
    <div class="key-box" style="margin-bottom: 25px;">
        <p>The Heuristic Evaluation indicates that the system fundamentally satisfies most baseline usability standards, yet suffers from critical friction points in the primary user flow (e.g., checkout and error state recovery).</p>
        <p>Immediate action should be prioritized for <strong>Critical</strong> and <strong>High</strong> severity issues, particularly addressing feedback delay and destructive action prevention. Resolving these will yield the highest return on investment in terms of user satisfaction and task completion rates.</p>
    </div>

    <!-- ================= APPENDIX ================= -->
    <div id="section-appendix" class="section-header">Appendix</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Severity</th>
                <th>Heuristic</th>
                <th>Title</th>
            </tr>
        </thead>

        <tbody>

            @foreach($report['findings'] as $finding)

            <tr>
                <td>{{ $finding['id'] }}</td>
                <td>{{ ucfirst($finding['severity']) }}</td>
                <td>{{ $finding['heuristic_id'] }}</td>
                <td>{{ $finding['title'] }}</td>
            </tr>

            @endforeach

        </tbody>
    </table>


</body>

</html>