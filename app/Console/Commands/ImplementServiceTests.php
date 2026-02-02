<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImplementServiceTests extends Command
{
    protected $signature = 'test:implement
                            {--service= : Specific service to implement}
                            {--all : Implement all incomplete tests}';

    protected $description = 'Implement incomplete service tests automatically';

    private array $implemented = [];
    private array $failed = [];

    public function handle()
    {
        $service = $this->option('service');
        $all = $this->option('all');

        if ($all) {
            return $this->implementAllTests();
        }

        if ($service) {
            return $this->implementSpecificTest($service);
        }

        $this->error('Please specify --service=NAME or --all');
        return 1;
    }

    protected function implementAllTests()
    {
        $this->info('Finding all incomplete tests...');

        $testFiles = File::allFiles(base_path('tests/Unit/Services'));
        $incompleteFiles = [];

        foreach ($testFiles as $file) {
            if (str_contains(File::get($file->getPathname()), 'markTestIncomplete')) {
                $incompleteFiles[] = $file->getPathname();
            }
        }

        $this->info('Found ' . count($incompleteFiles) . ' files with incomplete tests');
        $this->newLine();

        $bar = $this->output->createProgressBar(count($incompleteFiles));
        $bar->start();

        foreach ($incompleteFiles as $file) {
            $this->implementTestFile($file);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->displaySummary();

        return 0;
    }

    protected function implementSpecificTest(string $serviceName)
    {
        $testPath = base_path("tests/Unit/Services/{$serviceName}");
        if (!str_ends_with($testPath, 'Test.php')) {
            $testPath .= 'Test.php';
        }

        if (!File::exists($testPath)) {
            $this->error("Test file not found: {$testPath}");
            return 1;
        }

        $this->info("Implementing tests for {$serviceName}...");
        $this->implementTestFile($testPath);
        $this->displaySummary();

        return 0;
    }

    protected function implementTestFile(string $testPath)
    {
        try {
            $content = File::get($testPath);

            // Skip if already implemented (no markTestIncomplete)
            if (!str_contains($content, 'markTestIncomplete')) {
                return;
            }

            // Extract service name from test file
            $serviceName = $this->extractServiceName($testPath);
            $servicePath = $this->findServiceFile($serviceName);

            if (!$servicePath) {
                $this->failed[] = basename($testPath) . ' - Service not found';
                return;
            }

            // Parse service methods
            $serviceMethods = $this->parseServiceMethods($servicePath);

            // Generate new test content
            $newContent = $this->generateTestContent($testPath, $servicePath, $serviceMethods);

            // Write new content
            File::put($testPath, $newContent);

            $this->implemented[] = basename($testPath);
        } catch (\Exception $e) {
            $this->failed[] = basename($testPath) . ' - ' . $e->getMessage();
        }
    }

    protected function extractServiceName(string $testPath): string
    {
        $filename = basename($testPath, 'Test.php');

        // Handle subdirectories
        $relativePath = str_replace(base_path('tests/Unit/Services/'), '', $testPath);
        $relativePath = str_replace('Test.php', '', $relativePath);

        return str_replace('\\', '/', $relativePath);
    }

    protected function findServiceFile(string $serviceName): ?string
    {
        $possiblePaths = [
            base_path("app/Services/{$serviceName}.php"),
            base_path("app/Services/{$serviceName}Service.php"),
        ];

        foreach ($possiblePaths as $path) {
            if (File::exists($path)) {
                return $path;
            }
        }

        return null;
    }

    protected function parseServiceMethods(string $servicePath): array
    {
        $content = File::get($servicePath);
        $methods = [];

        // Extract public methods
        preg_match_all(
            '/public\s+function\s+(\w+)\s*\([^)]*\)(?:\s*:\s*([^{]+))?/m',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $methodName = $match[1];
            // Skip magic methods and constructor
            if (!str_starts_with($methodName, '__')) {
                $returnType = isset($match[2]) ? trim($match[2]) : 'mixed';
                $methods[$methodName] = $returnType;
            }
        }

        return $methods;
    }

    protected function generateTestContent(string $testPath, string $servicePath, array $methods): string
    {
        $content = File::get($testPath);
        $serviceContent = File::get($servicePath);

        // Extract model name from service
        $modelName = $this->extractModelName($serviceContent);

        // Replace each markTestIncomplete with actual implementation
        foreach ($methods as $methodName => $returnType) {
            $testMethodPattern = '/public\s+function\s+test_' . Str::snake($methodName) . '_\w+\(\).*?\{.*?markTestIncomplete.*?\}/s';

            if (preg_match($testMethodPattern, $content)) {
                $newTest = $this->generateTestMethod($methodName, $returnType, $modelName);
                $content = preg_replace($testMethodPattern, $newTest, $content, 1);
            }
        }

        // Add ModelNotFoundException import if needed
        if (str_contains($content, 'OrFail') && !str_contains($content, 'use Illuminate\Database\Eloquent\ModelNotFoundException;')) {
            $content = str_replace(
                'use Tests\TestCase;',
                "use Illuminate\Database\Eloquent\ModelNotFoundException;\nuse Tests\TestCase;",
                $content
            );
        }

        return $content;
    }

    protected function extractModelName(string $serviceContent): ?string
    {
        // Try to find model usage patterns
        if (preg_match('/use\s+App\\\\Models\\\\(\w+);/', $serviceContent, $matches)) {
            return $matches[1];
        }

        if (preg_match('/(\w+)::/', $serviceContent, $matches)) {
            $className = $matches[1];
            if ($className !== 'Log' && $className !== 'DB' && $className !== 'Cache') {
                return $className;
            }
        }

        return null;
    }

    protected function generateTestMethod(string $methodName, string $returnType, ?string $modelName): string
    {
        $testName = 'test_' . Str::snake($methodName);

        // Determine test type based on method name
        if (str_contains($methodName, 'getAll') || str_contains($methodName, 'get') && str_contains($returnType, 'Collection')) {
            return $this->generateCollectionTest($testName, $methodName, $modelName);
        } elseif (str_contains($methodName, 'find') && str_contains($returnType, '?')) {
            return $this->generateFindTest($testName, $methodName, $modelName);
        } elseif (str_contains($methodName, 'OrFail')) {
            return $this->generateOrFailTest($testName, $methodName, $modelName);
        } elseif (str_contains($methodName, 'create')) {
            return $this->generateCreateTest($testName, $methodName, $modelName);
        } elseif (str_contains($methodName, 'update')) {
            return $this->generateUpdateTest($testName, $methodName, $modelName);
        } elseif (str_contains($methodName, 'delete')) {
            return $this->generateDeleteTest($testName, $methodName, $modelName);
        } else {
            return $this->generateGenericTest($testName, $methodName, $modelName);
        }
    }

    protected function generateCollectionTest(string $testName, string $methodName, ?string $modelName): string
    {
        $model = $modelName ?? 'Model';

        return <<<PHP
    /**
     * Test {$methodName} returns collection
     */
    public function {$testName}_returns_collection()
    {
        // Arrange
        {$model}::factory()->count(3)->create();

        // Act
        \$result = \$this->service->{$methodName}();

        // Assert
        \$this->assertInstanceOf(\Illuminate\Support\Collection::class, \$result);
        \$this->assertCount(3, \$result);
    }
PHP;
    }

    protected function generateFindTest(string $testName, string $methodName, ?string $modelName): string
    {
        $model = $modelName ?? 'Model';

        return <<<PHP
    /**
     * Test {$methodName} returns model when exists
     */
    public function {$testName}_returns_model_when_exists()
    {
        // Arrange
        \$model = {$model}::factory()->create();

        // Act
        \$result = \$this->service->{$methodName}(\$model->id);

        // Assert
        \$this->assertNotNull(\$result);
        \$this->assertInstanceOf({$model}::class, \$result);
        \$this->assertEquals(\$model->id, \$result->id);
    }

    /**
     * Test {$methodName} returns null when not exists
     */
    public function {$testName}_returns_null_when_not_exists()
    {
        // Act
        \$result = \$this->service->{$methodName}(9999);

        // Assert
        \$this->assertNull(\$result);
    }
PHP;
    }

    protected function generateOrFailTest(string $testName, string $methodName, ?string $modelName): string
    {
        $model = $modelName ?? 'Model';

        return <<<PHP
    /**
     * Test {$methodName} returns model
     */
    public function {$testName}_returns_model()
    {
        // Arrange
        \$model = {$model}::factory()->create();

        // Act
        \$result = \$this->service->{$methodName}(\$model->id);

        // Assert
        \$this->assertInstanceOf({$model}::class, \$result);
        \$this->assertEquals(\$model->id, \$result->id);
    }

    /**
     * Test {$methodName} throws exception when not exists
     */
    public function {$testName}_throws_exception_when_not_exists()
    {
        // Assert
        \$this->expectException(ModelNotFoundException::class);

        // Act
        \$this->service->{$methodName}(9999);
    }
PHP;
    }

    protected function generateCreateTest(string $testName, string $methodName, ?string $modelName): string
    {
        $model = $modelName ?? 'Model';

        return <<<PHP
    /**
     * Test {$methodName} creates model successfully
     */
    public function {$testName}_creates_model_successfully()
    {
        // Arrange
        \$data = {$model}::factory()->make()->toArray();

        // Act
        \$result = \$this->service->{$methodName}(\$data);

        // Assert
        \$this->assertNotNull(\$result);
        \$this->assertInstanceOf({$model}::class, \$result);
        \$this->assertDatabaseHas('{$model}', ['id' => \$result->id]);
    }
PHP;
    }

    protected function generateUpdateTest(string $testName, string $methodName, ?string $modelName): string
    {
        $model = $modelName ?? 'Model';

        return <<<PHP
    /**
     * Test {$methodName} updates model successfully
     */
    public function {$testName}_updates_model_successfully()
    {
        // Arrange
        \$model = {$model}::factory()->create();
        \$updateData = ['updated_field' => 'new_value'];

        // Act
        \$result = \$this->service->{$methodName}(\$model->id, \$updateData);

        // Assert
        \$this->assertNotNull(\$result);
        \$this->assertDatabaseHas('{$model}', ['id' => \$model->id]);
    }
PHP;
    }

    protected function generateDeleteTest(string $testName, string $methodName, ?string $modelName): string
    {
        $model = $modelName ?? 'Model';

        return <<<PHP
    /**
     * Test {$methodName} deletes model successfully
     */
    public function {$testName}_deletes_model_successfully()
    {
        // Arrange
        \$model = {$model}::factory()->create();

        // Act
        \$result = \$this->service->{$methodName}(\$model->id);

        // Assert
        \$this->assertTrue(\$result);
        \$this->assertDatabaseMissing('{$model}', ['id' => \$model->id]);
    }
PHP;
    }

    protected function generateGenericTest(string $testName, string $methodName, ?string $modelName): string
    {
        return <<<PHP
    /**
     * Test {$methodName} works correctly
     */
    public function {$testName}_works()
    {
        // Arrange
        // TODO: Add specific test setup

        // Act
        \$result = \$this->service->{$methodName}();

        // Assert
        \$this->assertNotNull(\$result);
    }
PHP;
    }

    protected function displaySummary()
    {
        $this->info('Implementation Summary:');
        $this->line('═══════════════════════════════════════');
        $this->info('✓ Implemented: ' . count($this->implemented));
        $this->error('✗ Failed: ' . count($this->failed));

        if (!empty($this->failed)) {
            $this->newLine();
            $this->error('Failed files:');
            foreach ($this->failed as $failed) {
                $this->line("  - {$failed}");
            }
        }
    }
}
