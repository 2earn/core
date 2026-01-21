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
                            {--open : Open the report in browser after generation}';

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
            $this->newLine();

            $process = new Process(['php', 'artisan', 'test']);
            $process->setTimeout(300); // 5 minutes timeout
            $process->setWorkingDirectory(base_path());

            try {
                $process->run(function ($type, $buffer) {
                    echo $buffer;
                });

                if (!$process->isSuccessful()) {
                    $this->warn('⚠️  Some tests failed, but continuing with report generation...');
                }
            } catch (ProcessFailedException $exception) {
                $this->error('❌ Failed to run tests');
                $this->error($exception->getMessage());
                return Command::FAILURE;
            }

            $this->newLine();
        } else {
            $this->info('⏭️  Skipping test execution, using existing results...');
            $this->newLine();
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

        $suite = [
            'name' => $displayName,
            'full_name' => $suiteName,
            'tests' => (int) $testsuite['tests'],
            'failures' => (int) $testsuite['failures'],
            'errors' => (int) $testsuite['errors'],
            'passed' => (int) $testsuite['tests'] - (int) $testsuite['failures'] - (int) $testsuite['errors'],
            'time' => (float) $testsuite['time'],
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
}
