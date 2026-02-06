<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GenerateTestReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:report
                            {--skip-tests : Skip running tests and use existing results}
                            {--open : Open the report in browser after generation}
                            {--timeout=1800 : Maximum execution time in seconds}
                            {--exclude-group=* : Exclude test groups}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run PHPUnit tests and generate a beautiful HTML report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Test Report Generator');
        $this->newLine();

        // Step 1: Run tests (unless skipped)
        if (!$this->option('skip-tests')) {
            $this->info('📝 Running tests...');

            // Determine which groups to exclude
            $excludeGroups = $this->option('exclude-group');

            if (!empty($excludeGroups)) {
                $this->comment('   Excluding groups: ' . implode(', ', $excludeGroups));
            }

            $this->newLine();

            // First, get total test count
            $countProcess = new Process(['php', 'artisan', 'test', '--list-tests']);
            $countProcess->setTimeout(60);
            $countProcess->setWorkingDirectory(base_path());
            $countProcess->run();

            $totalTests = 0;
            if ($countProcess->isSuccessful()) {
                $output = $countProcess->getOutput();
                // Count lines that look like test methods
                preg_match_all('/^\s*-/m', $output, $matches);
                $totalTests = count($matches[0]);
            }

            // Display test count
            if ($totalTests > 0) {
                $this->info("📊 {$totalTests} tests will be executed");
            } else {
                $this->warn('⚠️  Could not determine total test count');
            }

            $this->newLine();

            // Run tests
            $testCommand = ['php', 'artisan', 'test'];

            // Add exclude-group arguments to command
            foreach ($excludeGroups as $group) {
                $testCommand[] = '--exclude-group=' . $group;
            }

            $process = new Process($testCommand);
            $process->setTimeout((int) $this->option('timeout')); // Configurable timeout
            $process->setWorkingDirectory(base_path());

            try {
                $process->run(function ($type, $buffer) {
                    if ($type === Process::OUT) {
                        echo $buffer;
                    }
                });

                $this->newLine();

                if (!$process->isSuccessful()) {
                    $this->warn('⚠️  Some tests failed, but continuing with report generation...');
                }

            } catch (ProcessFailedException $exception) {
                $this->newLine();
                $this->error('❌ Failed to run tests');
                $this->error($exception->getMessage());
                return Command::FAILURE;
            } catch (\Exception $exception) {
                $this->newLine();
                $this->error('❌ Test execution timed out or failed');
                $this->error($exception->getMessage());
                $this->warn('⚠️  Attempting to generate report from partial results...');
            }
        } else {
            $this->info('⏭️  Skipping test execution, using existing results...');
        }

        // Step 2: Parse JUnit XML
        $junitPath = base_path('tests/reports/junit.xml');

        if (!File::exists($junitPath)) {
            $this->error('❌ JUnit XML file not found at: ' . $junitPath);
            $this->error('   Please run tests first or check your phpunit.xml configuration.');
            return Command::FAILURE;
        }

        $this->info('📊 Parsing test results...');

        $xml = simplexml_load_file($junitPath);
        if ($xml === false) {
            $this->error('❌ Failed to parse JUnit XML file');
            return Command::FAILURE;
        }

        // Extract statistics
        $rootSuite = $xml->testsuite[0];
        $stats = [
            'total' => (int) $rootSuite['tests'],
            'failures' => (int) $rootSuite['failures'],
            'errors' => (int) $rootSuite['errors'],
            'skipped' => (int) ($rootSuite['skipped'] ?? 0),
            'time' => (float) $rootSuite['time'],
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];

        $stats['passed'] = $stats['total'] - $stats['failures'] - $stats['errors'] - $stats['skipped'];
        $stats['success_rate'] = $stats['total'] > 0 ? round(($stats['passed'] / $stats['total']) * 100, 2) : 0;

        // Extract test suites
        $testSuites = [];
        $this->parseTestSuites($rootSuite, $testSuites);

        // Step 3: Generate HTML report
        $this->info('🎨 Generating HTML report...');

        $html = view('test-report', [
            'stats' => $stats,
            'testSuites' => $testSuites
        ])->render();

        // Step 4: Save report
        $reportPath = base_path('tests/reports/test-report.html');
        File::put($reportPath, $html);

        // Success messages
        $this->newLine();
        $this->info('✅ Test report generated successfully!');
        $this->newLine();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Tests', $stats['total']],
                ['Passed', $stats['passed']],
                ['Failed', $stats['failures']],
                ['Errors', $stats['errors']],
                ['Skipped', $stats['skipped']],
                ['Success Rate', $stats['success_rate'] . '%'],
                ['Total Time', round($stats['time'], 2) . 's'],
            ]
        );

        $this->newLine();
        $this->info('📁 Report location: ' . $reportPath);

        // Open in browser if requested
        if ($this->option('open')) {
            $this->info('🌐 Opening report in browser...');

            if (PHP_OS_FAMILY === 'Windows') {
                exec('start "" "' . $reportPath . '"');
            } elseif (PHP_OS_FAMILY === 'Darwin') {
                exec('open "' . $reportPath . '"');
            } else {
                exec('xdg-open "' . $reportPath . '"');
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Recursively parse test suites from XML
     */
    private function parseTestSuites($testsuite, &$testSuites)
    {
        // Skip if this is just a wrapper with no actual tests
        if (!isset($testsuite->testcase) && count($testsuite->testsuite) > 0) {
            foreach ($testsuite->testsuite as $childSuite) {
                $this->parseTestSuites($childSuite, $testSuites);
            }
            return;
        }

        // Only process testsuites that have actual test cases
        if (!isset($testsuite->testcase) || count($testsuite->testcase) == 0) {
            return;
        }

        $suiteName = (string) $testsuite['name'];

        // Extract just the class name from the full namespace
        $displayName = $suiteName;
        if (strpos($suiteName, '\\') !== false) {
            $parts = explode('\\', $suiteName);
            $displayName = end($parts);
        }

        // Extract groups from test class file
        $groups = $this->extractGroupsFromTestClass($suiteName);

        $suite = [
            'name' => $displayName,
            'full_name' => $suiteName,
            'tests' => (int) $testsuite['tests'],
            'failures' => (int) $testsuite['failures'],
            'errors' => (int) $testsuite['errors'],
            'passed' => (int) $testsuite['tests'] - (int) $testsuite['failures'] - (int) $testsuite['errors'],
            'time' => (float) $testsuite['time'],
            'groups' => $groups,
            'test_cases' => []
        ];

        // Process each test case
        foreach ($testsuite->testcase as $testcase) {
            $testName = (string) $testcase['name'];

            $status = 'passed';
            $message = null;

            if (isset($testcase->failure)) {
                $status = 'failed';
                $message = (string) $testcase->failure;
            } elseif (isset($testcase->error)) {
                $status = 'failed';
                $message = (string) $testcase->error;
            } elseif (isset($testcase->skipped)) {
                $status = 'skipped';
                $message = (string) $testcase->skipped;
            }

            // Convert test name to readable format
            $readableName = str_replace(['test_', '_'], ['', ' '], $testName);
            $readableName = ucfirst(trim($readableName));

            $suite['test_cases'][] = [
                'name' => $readableName,
                'original_name' => $testName,
                'status' => $status,
                'time' => (float) $testcase['time'],
                'message' => $message
            ];
        }

        $testSuites[] = $suite;
    }

    /**
     * Extract @group annotations from test class file
     */
    private function extractGroupsFromTestClass(string $className): array
    {
        $groups = [];

        try {
            // Convert namespace to file path
            $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className);
            $possiblePaths = [
                base_path('tests/' . str_replace('Tests\\', '', $classPath) . '.php'),
                base_path($classPath . '.php'),
            ];

            $filePath = null;
            foreach ($possiblePaths as $path) {
                if (File::exists($path)) {
                    $filePath = $path;
                    break;
                }
            }

            if (!$filePath) {
                return $groups;
            }

            $content = File::get($filePath);

            // Extract @group annotations from class-level PHPDoc
            if (preg_match('/\/\*\*.*?class\s+\w+/s', $content, $matches)) {
                $docBlock = $matches[0];
                if (preg_match_all('/@group\s+(\w+)/', $docBlock, $groupMatches)) {
                    $groups = array_merge($groups, $groupMatches[1]);
                }
            }

            // Extract #[Group] attributes (PHPUnit 10+) - supports single quotes, double quotes, and no quotes
            // Matches: #[Group('name')], #[Group("name")], #[Group('name')]
            if (preg_match_all('/#\s*\[\s*Group\s*\(\s*[\'"](\w+)[\'"]\s*\)\s*\]/', $content, $attributeMatches)) {
                $groups = array_merge($groups, $attributeMatches[1]);
            }

            // Remove duplicates and return
            $groups = array_unique($groups);

        } catch (\Exception $e) {
            // Silently fail - groups are optional
        }

        return array_values($groups);
    }
}
