#!/usr/bin/env php
<?php
/**
 * Generate Enhanced HTML Test Report
 *
 * This script parses the JUnit XML test results and generates
 * a beautifully styled HTML report with statistics and details.
 */

$junitFile = __DIR__ . '/tests/reports/junit.xml';
$outputFile = __DIR__ . '/tests/reports/test-report.html';

if (!file_exists($junitFile)) {
    echo "Error: JUnit XML file not found at: $junitFile\n";
    echo "Please run 'php artisan test' first to generate the test results.\n";
    exit(1);
}

// Parse JUnit XML
$xml = simplexml_load_file($junitFile);
if ($xml === false) {
    echo "Error: Failed to parse JUnit XML file.\n";
    exit(1);
}

// Extract test statistics from root testsuite
$rootSuite = $xml->testsuite[0];
$totalTests = (int) $rootSuite['tests'];
$totalFailures = (int) $rootSuite['failures'];
$totalErrors = (int) $rootSuite['errors'];
$totalSkipped = (int) ($rootSuite['skipped'] ?? 0);
$totalPassed = $totalTests - $totalFailures - $totalErrors - $totalSkipped;
$totalTime = (float) $rootSuite['time'];
$timestamp = date('Y-m-d H:i:s');

// Calculate success rate
$successRate = $totalTests > 0 ? round(($totalPassed / $totalTests) * 100, 2) : 0;

// Generate HTML
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Report - 2earn Application</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header .timestamp {
            opacity: 0.9;
            font-size: 0.95em;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 40px;
            background: #f8f9fa;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .number {
            font-size: 3em;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-card .label {
            color: #666;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-card.passed .number { color: #28a745; }
        .stat-card.failed .number { color: #dc3545; }
        .stat-card.skipped .number { color: #ffc107; }
        .stat-card.total .number { color: #667eea; }
        .stat-card.time .number { font-size: 2em; }
        .stat-card.rate .number { color: #17a2b8; }

        .progress-bar {
            margin: 20px 40px;
            height: 40px;
            background: #e9ecef;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1em;
            transition: width 1s ease;
        }

        .test-suites {
            padding: 40px;
        }

        .test-suite {
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
        }

        .test-suite-header {
            background: #f8f9fa;
            padding: 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e9ecef;
        }

        .test-suite-header:hover {
            background: #e9ecef;
        }

        .test-suite-title {
            font-size: 1.3em;
            font-weight: 600;
            color: #333;
        }

        .test-suite-stats {
            display: flex;
            gap: 20px;
        }

        .test-suite-stat {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
        }

        .test-suite-stat.passed {
            background: #d4edda;
            color: #155724;
        }

        .test-suite-stat.failed {
            background: #f8d7da;
            color: #721c24;
        }

        .test-cases {
            background: white;
        }

        .test-case {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f3f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .test-case:last-child {
            border-bottom: none;
        }

        .test-case-name {
            flex: 1;
        }

        .test-case-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .status-badge.passed {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.failed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.skipped {
            background: #fff3cd;
            color: #856404;
        }

        .test-case-time {
            color: #666;
            font-size: 0.9em;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 1px solid #e9ecef;
        }

        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }

        .icon.passed::before { content: '✓'; color: #28a745; }
        .icon.failed::before { content: '✗'; color: #dc3545; }
        .icon.skipped::before { content: '⊘'; color: #ffc107; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Test Report</h1>
            <p class="timestamp">Generated on: {$timestamp}</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card total">
                <div class="label">Total Tests</div>
                <div class="number">{$totalTests}</div>
            </div>
            <div class="stat-card passed">
                <div class="label">Passed</div>
                <div class="number">{$totalPassed}</div>
            </div>
            <div class="stat-card failed">
                <div class="label">Failed</div>
                <div class="number">{$totalFailures}</div>
            </div>
            <div class="stat-card skipped">
                <div class="label">Skipped</div>
                <div class="number">{$totalSkipped}</div>
            </div>
            <div class="stat-card rate">
                <div class="label">Success Rate</div>
                <div class="number">{$successRate}%</div>
            </div>
            <div class="stat-card time">
                <div class="label">Total Time</div>
                <div class="number">{$totalTime}s</div>
            </div>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" style="width: {$successRate}%">
                {$successRate}% Passed
            </div>
        </div>

        <div class="test-suites">
            <h2 style="margin-bottom: 20px; color: #333;">Test Suites</h2>

HTML;

// Function to recursively process testsuites
function processTestSuite($testsuite, &$html, $depth = 0) {
    // Skip if this is just a wrapper with no actual tests
    if (!isset($testsuite->testcase) && count($testsuite->testsuite) > 0) {
        foreach ($testsuite->testsuite as $childSuite) {
            processTestSuite($childSuite, $html, $depth);
        }
        return;
    }

    // Only process testsuites that have actual test cases
    if (!isset($testsuite->testcase) || count($testsuite->testcase) == 0) {
        return;
    }

    $suiteName = (string) $testsuite['name'];
    $suiteTests = (int) $testsuite['tests'];
    $suiteFailures = (int) $testsuite['failures'];
    $suiteErrors = (int) $testsuite['errors'];
    $suitePassed = $suiteTests - $suiteFailures - $suiteErrors;

    // Extract just the class name from the full namespace
    $displayName = $suiteName;
    if (strpos($suiteName, '\\') !== false) {
        $parts = explode('\\', $suiteName);
        $displayName = end($parts);
    }

    $html .= <<<HTML
            <div class="test-suite">
                <div class="test-suite-header">
                    <div class="test-suite-title">{$displayName}</div>
                    <div class="test-suite-stats">
                        <span class="test-suite-stat passed">{$suitePassed} passed</span>
                        <span class="test-suite-stat failed">{$suiteFailures} failed</span>
                    </div>
                </div>
                <div class="test-cases">

HTML;

    // Process each test case
    foreach ($testsuite->testcase as $testcase) {
        $testName = (string) $testcase['name'];
        $testTime = number_format((float) $testcase['time'], 3);

        $status = 'passed';
        $statusIcon = 'passed';

        if (isset($testcase->failure)) {
            $status = 'failed';
            $statusIcon = 'failed';
        } elseif (isset($testcase->error)) {
            $status = 'failed';
            $statusIcon = 'failed';
        } elseif (isset($testcase->skipped)) {
            $status = 'skipped';
            $statusIcon = 'skipped';
        }

        // Convert test name to readable format
        $readableName = str_replace(['test_', '_'], ['', ' '], $testName);
        $readableName = ucfirst(trim($readableName));

        $html .= <<<HTML
                    <div class="test-case">
                        <div class="test-case-name">
                            <span class="icon {$statusIcon}"></span>
                            {$readableName}
                        </div>
                        <div class="test-case-status">
                            <span class="test-case-time">{$testTime}s</span>
                            <span class="status-badge {$status}">{$status}</span>
                        </div>
                    </div>

HTML;
    }

    $html .= <<<HTML
                </div>
            </div>

HTML;
}

// Process test suites (Unit and Feature)
$rootSuite = $xml->testsuite[0];
foreach ($rootSuite->testsuite as $mainSuite) {
    foreach ($mainSuite->testsuite as $testSuite) {
        processTestSuite($testSuite, $html);
    }
}

$html .= <<<HTML
        </div>

        <div class="footer">
            <p><strong>2earn Application</strong> - Test Suite Report</p>
            <p>Generated automatically by PHPUnit</p>
        </div>
    </div>
</body>
</html>
HTML;

// Write HTML file
file_put_contents($outputFile, $html);

echo "✓ Enhanced HTML test report generated successfully!\n";
echo "  Location: {$outputFile}\n";
echo "  Total Tests: {$totalTests}\n";
echo "  Passed: {$totalPassed}\n";
echo "  Failed: {$totalFailures}\n";
echo "  Success Rate: {$successRate}%\n";
echo "\n";
echo "Open the report in your browser:\n";
echo "  file:///{$outputFile}\n";
