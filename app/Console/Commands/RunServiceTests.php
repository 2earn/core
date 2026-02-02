<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class RunServiceTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:services
                            {action=menu : The action to perform (all|services|complete|specific|coverage|parallel|list|status|html)}
                            {--service= : Specific service test to run (e.g., AmountServiceTest)}
                            {--open : Open HTML report in browser after generation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run service tests with various options and generate HTML reports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $service = $this->option('service');
        $open = $this->option('open');

        if ($action === 'menu') {
            return $this->showMenu();
        }

        return match($action) {
            'all' => $this->runAllTests(),
            'services' => $this->runServiceTests(),
            'complete' => $this->runCompleteTests(),
            'specific' => $this->runSpecificTest($service),
            'coverage' => $this->runCoverageTests(),
            'parallel' => $this->runParallelTests(),
            'list' => $this->listTests(),
            'status' => $this->showStatus(),
            'html' => $this->generateHtmlReport($open),
            default => $this->error("Invalid action: {$action}") ?? 1
        };
    }

    protected function showMenu()
    {
        $this->info('╔════════════════════════════════════════╗');
        $this->info('║     Service Tests Runner - Menu        ║');
        $this->info('╚════════════════════════════════════════╝');
        $this->newLine();

        $choices = [
            'all' => 'Run ALL Unit Tests',
            'services' => 'Run ONLY Service Tests',
            'complete' => 'Run Complete Tests (exclude incomplete)',
            'specific' => 'Run Specific Service Test',
            'coverage' => 'Run Tests with Coverage',
            'parallel' => 'Run Tests in Parallel',
            'list' => 'List All Service Tests',
            'status' => 'Show Implementation Status',
            'html' => 'Generate HTML Report',
            'exit' => 'Exit'
        ];

        $choice = $this->choice('Select an action:', array_values($choices));

        $action = array_search($choice, $choices);

        if ($action === 'exit') {
            $this->info('Goodbye!');
            return 0;
        }

        if ($action === 'specific') {
            $service = $this->ask('Enter service test name (e.g., AmountServiceTest)');
            return $this->runSpecificTest($service);
        }

        if ($action === 'html') {
            $open = $this->confirm('Open report in browser?', true);
            return $this->generateHtmlReport($open);
        }

        // Map menu actions to method names
        $methodMap = [
            'all' => 'runAllTests',
            'services' => 'runServiceTests',
            'complete' => 'runCompleteTests',
            'coverage' => 'runCoverageTests',
            'parallel' => 'runParallelTests',
            'list' => 'listTests',
            'status' => 'showStatus',
        ];

        if (isset($methodMap[$action])) {
            return $this->{$methodMap[$action]}();
        }

        return 1;
    }

    protected function getMethodName(string $action): string
    {
        return 'run' . str_replace(' ', '', ucwords(str_replace('-', ' ', $action))) . 'Tests';
    }

    protected function runAllTests()
    {
        $this->info('Running ALL Unit Tests...');
        $this->newLine();

        return $this->executeProcess(['php', 'artisan', 'test', '--testsuite=Unit']);
    }

    protected function runServiceTests()
    {
        $this->info('Running Service Tests...');
        $this->newLine();

        return $this->executeProcess(['php', 'artisan', 'test', 'tests/Unit/Services/']);
    }

    protected function runCompleteTests()
    {
        $this->info('Running Complete Service Tests (excluding incomplete)...');
        $this->newLine();

        return $this->executeProcess(['php', 'artisan', 'test', 'tests/Unit/Services/', '--exclude-group=incomplete']);
    }

    protected function runSpecificTest(?string $service)
    {
        if (empty($service)) {
            $this->error('Service test name is required!');
            $this->info('Example: php artisan test:services specific --service=AmountServiceTest');
            return 1;
        }

        $testPath = "tests/Unit/Services/{$service}";
        if (!str_ends_with($testPath, '.php')) {
            $testPath .= '.php';
        }

        if (!File::exists(base_path($testPath))) {
            $this->error("Test file not found: {$testPath}");
            return 1;
        }

        $this->info("Running {$testPath}...");
        $this->newLine();

        return $this->executeProcess(['php', 'artisan', 'test', $testPath]);
    }

    protected function runCoverageTests()
    {
        $this->info('Running Tests with Coverage...');
        $this->warn('Note: Requires Xdebug to be installed');
        $this->newLine();

        return $this->executeProcess(['php', 'artisan', 'test', 'tests/Unit/Services/', '--coverage', '--min=70']);
    }

    protected function runParallelTests()
    {
        $this->info('Running Tests in Parallel (Faster)...');
        $this->newLine();

        return $this->executeProcess(['php', 'artisan', 'test', 'tests/Unit/Services/', '--parallel']);
    }

    protected function listTests()
    {
        $this->info('Listing All Service Test Files...');
        $this->newLine();

        $testFiles = File::allFiles(base_path('tests/Unit/Services'));
        $phpFiles = array_filter($testFiles, fn($file) => str_ends_with($file->getFilename(), 'Test.php'));

        foreach ($phpFiles as $file) {
            $relativePath = str_replace(base_path('tests/Unit/Services/'), '', $file->getPathname());
            $this->line("  <fg=green>{$relativePath}</>");
        }

        $this->newLine();
        $this->info('Total: ' . count($phpFiles) . ' test files');

        return 0;
    }

    protected function showStatus()
    {
        $this->info('Test Implementation Status');
        $this->line('════════════════════════════════════════');
        $this->newLine();

        $this->info('Fully Implemented Tests:');
        $implemented = [
            'AmountServiceTest.php' => 8,
            'CountryServiceTest.php' => 4,
            'UserGuide/UserGuideServiceTest.php' => 20,
            'Items/ItemServiceTest.php' => 17,
            'EventServiceTest.php' => 13,
            'CashServiceTest.php' => 5,
            'CommentServiceTest.php' => 9,
        ];

        foreach ($implemented as $file => $count) {
            $this->line("  <fg=green>[OK]</> {$file} ({$count} tests)");
        }

        $this->newLine();
        $this->comment('Statistics:');
        $this->line("  Total Test Files: <fg=white>83+</>");
        $this->line("  Implemented: <fg=green>7 (76+ test methods)</>");
        $this->line("  Remaining: <fg=yellow>76+</>");
        $this->newLine();

        $this->info('For detailed status, see: SERVICE_TESTS_STATUS.md');

        return 0;
    }

    protected function generateHtmlReport(bool $open = false)
    {
        $this->info('Generating HTML Test Report...');
        $this->newLine();

        $reportDir = base_path('tests/reports');
        if (!File::exists($reportDir)) {
            File::makeDirectory($reportDir, 0755, true);
            $this->info('Created reports directory');
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $htmlReport = "{$reportDir}/service-tests-{$timestamp}.html";
        $junitReport = "{$reportDir}/junit-{$timestamp}.xml";

        // Run tests and capture output
        $this->info('Running tests...');
        $process = new Process(['php', 'artisan', 'test', 'tests/Unit/Services/', '--log-junit=' . $junitReport]);
        $process->setWorkingDirectory(base_path());
        $process->run();

        $testOutput = $process->getOutput() . $process->getErrorOutput();

        // Generate HTML content
        $this->info('Creating HTML report...');
        $htmlContent = $this->buildHtmlReport($timestamp, $testOutput, $junitReport, $htmlReport);

        File::put($htmlReport, $htmlContent);

        $this->newLine();
        $this->info('✓ Reports generated successfully!');
        $this->newLine();
        $this->line("<fg=cyan>HTML Report:</> {$htmlReport}");
        $this->line("<fg=cyan>JUnit XML:  </> {$junitReport}");
        $this->newLine();

        if ($open) {
            $this->info('Opening HTML report in browser...');
            $this->openInBrowser($htmlReport);
        } else {
            $this->comment('To open in browser, use: --open flag');
        }

        return 0;
    }

    protected function buildHtmlReport(string $timestamp, string $testOutput, string $junitPath, string $htmlPath): string
    {
        $stats = $this->parseJunitReport($junitPath);
        $passRate = $stats['total'] > 0 ? round(($stats['passed'] / $stats['total']) * 100, 2) : 0;

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Tests Report - {$timestamp}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
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
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .timestamp {
            opacity: 0.9;
            font-size: 0.9em;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 30px;
            background: #f8f9fa;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .stat-value {
            font-size: 2.5em;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-label {
            color: #666;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .passed .stat-value { color: #10b981; }
        .failed .stat-value { color: #ef4444; }
        .skipped .stat-value { color: #f59e0b; }
        .total .stat-value { color: #3b82f6; }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 1.5em;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
            color: #667eea;
        }
        .progress-bar {
            height: 40px;
            background: #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1em;
            transition: width 0.5s ease;
        }
        .test-output {
            background: #1e293b;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 600px;
            overflow-y: auto;
        }
        .implemented-list {
            display: grid;
            gap: 15px;
        }
        .test-file {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .test-file-name {
            font-weight: bold;
            color: #667eea;
        }
        .test-count {
            color: #666;
            font-size: 0.9em;
        }
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            background: #10b981;
            color: white;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 1px solid #e5e7eb;
        }
        .command-example {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>&#x1F9EA; Service Tests Report</h1>
            <div class="timestamp">Generated: {$timestamp}</div>
        </div>

        <div class="stats">
            <div class="stat-card total">
                <div class="stat-label">Total Tests</div>
                <div class="stat-value">{$stats['total']}</div>
            </div>
            <div class="stat-card passed">
                <div class="stat-label">Passed</div>
                <div class="stat-value">{$stats['passed']}</div>
            </div>
            <div class="stat-card failed">
                <div class="stat-label">Failed</div>
                <div class="stat-value">{$stats['failed']}</div>
            </div>
            <div class="stat-card skipped">
                <div class="stat-label">Skipped</div>
                <div class="stat-value">{$stats['skipped']}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Execution Time</div>
                <div class="stat-value">{$stats['time']}s</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pass Rate</div>
                <div class="stat-value">{$passRate}%</div>
            </div>
        </div>

        <div class="content">
            <div class="progress-bar">
                <div class="progress-fill" style="width: {$passRate}%">{$passRate}%</div>
            </div>

            <div class="section">
                <h2 class="section-title">&#x2705; Fully Implemented Tests</h2>
                <div class="implemented-list">
                    <div class="test-file">
                        <div>
                            <div class="test-file-name">AmountServiceTest.php</div>
                            <div class="test-count">8 test methods</div>
                        </div>
                        <span class="badge">Complete</span>
                    </div>
                    <div class="test-file">
                        <div>
                            <div class="test-file-name">CountryServiceTest.php</div>
                            <div class="test-count">4 test methods</div>
                        </div>
                        <span class="badge">Complete</span>
                    </div>
                    <div class="test-file">
                        <div>
                            <div class="test-file-name">UserGuide/UserGuideServiceTest.php</div>
                            <div class="test-count">20 test methods</div>
                        </div>
                        <span class="badge">Complete</span>
                    </div>
                    <div class="test-file">
                        <div>
                            <div class="test-file-name">Items/ItemServiceTest.php</div>
                            <div class="test-count">17 test methods</div>
                        </div>
                        <span class="badge">Complete</span>
                    </div>
                    <div class="test-file">
                        <div>
                            <div class="test-file-name">EventServiceTest.php</div>
                            <div class="test-count">13 test methods</div>
                        </div>
                        <span class="badge">Complete</span>
                    </div>
                    <div class="test-file">
                        <div>
                            <div class="test-file-name">CashServiceTest.php</div>
                            <div class="test-count">5 test methods</div>
                        </div>
                        <span class="badge">Complete</span>
                    </div>
                    <div class="test-file">
                        <div>
                            <div class="test-file-name">CommentServiceTest.php</div>
                            <div class="test-count">9 test methods</div>
                        </div>
                        <span class="badge">Complete</span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">&#x1F4CA; Test Output</h2>
                <div class="test-output">{$this->escapeHtml($testOutput)}</div>
            </div>

            <div class="section">
                <h2 class="section-title">&#x1F4BB; Usage Commands</h2>
                <div class="command-example">
                    <div><strong># Run all service tests</strong></div>
                    <div>php artisan test:services services</div>
                </div>
                <div class="command-example">
                    <div><strong># Run specific test</strong></div>
                    <div>php artisan test:services specific --service=AmountServiceTest</div>
                </div>
                <div class="command-example">
                    <div><strong># Generate HTML report</strong></div>
                    <div>php artisan test:services html --open</div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">&#x1F4C1; Report Files</h2>
                <p><strong>HTML Report:</strong> {$htmlPath}</p>
                <p><strong>JUnit XML:</strong> {$junitPath}</p>
            </div>
        </div>

        <div class="footer">
            <p><strong>Generated by Laravel Artisan Command</strong></p>
            <p>Service Tests Runner • 2earn Platform</p>
            <p>For more information, see SERVICE_TESTS_STATUS.md</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    protected function parseJunitReport(string $path): array
    {
        $stats = [
            'total' => 0,
            'passed' => 0,
            'failed' => 0,
            'skipped' => 0,
            'time' => 0.0
        ];

        if (!File::exists($path)) {
            return $stats;
        }

        try {
            $xml = simplexml_load_file($path);
            if ($xml && isset($xml->testsuite)) {
                $stats['total'] = (int) $xml->testsuite['tests'];
                $stats['failed'] = (int) $xml->testsuite['failures'] + (int) $xml->testsuite['errors'];
                $stats['skipped'] = (int) $xml->testsuite['skipped'];
                $stats['passed'] = $stats['total'] - $stats['failed'] - $stats['skipped'];
                $stats['time'] = round((float) $xml->testsuite['time'], 2);
            }
        } catch (\Exception $e) {
            // If parsing fails, return defaults
        }

        return $stats;
    }

    protected function escapeHtml(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    protected function openInBrowser(string $path)
    {
        $os = PHP_OS_FAMILY;

        $command = match($os) {
            'Windows' => ['cmd', '/c', 'start', '""', $path],
            'Darwin' => ['open', $path],
            default => ['xdg-open', $path],
        };

        $process = new Process($command);
        $process->run();
    }

    protected function executeProcess(array $command): int
    {
        $process = new Process($command);
        $process->setWorkingDirectory(base_path());
        $process->setTimeout(null);
        $process->setTty(Process::isTtySupported());

        return $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
