# Service Unit Tests Documentation

## Overview

This directory contains comprehensive PHPUnit tests for all service classes in the `app/Services` directory. Each service has a corresponding test file that covers all public methods.

## Directory Structure

```
tests/Unit/Services/
├── AmountServiceTest.php
├── CountryServiceTest.php
├── EventServiceTest.php
├── Items/
│   └── ItemServiceTest.php
└── UserGuide/
    └── UserGuideServiceTest.php
```

The test directory structure mirrors the `app/Services` directory structure.

## Test Structure

Each test class follows this pattern:

```php
<?php

namespace Tests\Unit\Services;

use App\Services\YourService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YourServiceTest extends TestCase
{
    use RefreshDatabase;

    protected YourService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new YourService();
    }

    /**
     * Test description following convention
     */
    public function test_method_name_scenario_expected_result()
    {
        // Arrange - Set up test data
        
        // Act - Execute the method being tested
        
        // Assert - Verify the results
    }
}
```

## Test Naming Convention

Tests follow the pattern: `test_{method_name}_{scenario}_{expected_result}`

Examples:
- `test_get_by_id_returns_model_when_exists()`
- `test_update_returns_false_when_not_found()`
- `test_delete_successfully_deletes_record()`

## Running Tests

### Run all service tests
```bash
php artisan test --testsuite=Unit
```

### Run specific service test
```bash
php artisan test tests/Unit/Services/AmountServiceTest.php
```

### Run specific test method
```bash
php artisan test --filter test_get_by_id_returns_amount_when_exists
```

### Run with coverage (if xdebug is enabled)
```bash
php artisan test --coverage
```

## Test Coverage

Each service test should cover:

1. **Happy path scenarios** - Methods working as expected with valid data
2. **Edge cases** - Empty results, null values, boundary conditions
3. **Error cases** - Invalid IDs, not found scenarios, exceptions
4. **Data validation** - Ensuring correct data types and structures are returned
5. **Database interactions** - Verifying database state after operations

## Best Practices

### 1. Use Factories
Always use model factories for creating test data:

```php
$user = User::factory()->create();
$platform = Platform::factory()->create(['name' => 'Test Platform']);
```

### 2. Use RefreshDatabase Trait
This ensures a clean database state for each test:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourServiceTest extends TestCase
{
    use RefreshDatabase;
}
```

### 3. Arrange-Act-Assert Pattern
Structure each test with clear sections:

```php
public function test_example()
{
    // Arrange
    $data = ['key' => 'value'];
    
    // Act
    $result = $this->service->method($data);
    
    // Assert
    $this->assertEquals('expected', $result);
}
```

### 4. Test One Thing Per Test
Each test should focus on a single behavior:

```php
// Good
public function test_create_saves_to_database()
public function test_create_returns_model_instance()

// Bad
public function test_create() // Too broad
```

### 5. Use Descriptive Assertions
Choose the most specific assertion:

```php
// Good
$this->assertInstanceOf(User::class, $result);
$this->assertCount(5, $collection);
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);

// Less specific
$this->assertTrue($result instanceof User);
$this->assertEquals(5, count($collection));
```

## Common Assertions

### Model/Object Assertions
```php
$this->assertInstanceOf(Model::class, $result);
$this->assertNull($result);
$this->assertNotNull($result);
$this->assertEquals($expected, $actual);
```

### Database Assertions
```php
$this->assertDatabaseHas('table_name', ['column' => 'value']);
$this->assertDatabaseMissing('table_name', ['id' => $deletedId]);
$this->assertDatabaseCount('table_name', 5);
```

### Collection Assertions
```php
$this->assertCount(5, $collection);
$this->assertTrue($collection->isEmpty());
$this->assertFalse($collection->isEmpty());
```

### Boolean Assertions
```php
$this->assertTrue($result);
$this->assertFalse($result);
```

### Exception Assertions
```php
$this->expectException(ModelNotFoundException::class);
$this->service->methodThatThrows();
```

## Creating Tests for New Services

### Step 1: Create Test File
Create a new test file matching the service location:
- Service: `app/Services/Example/ExampleService.php`
- Test: `tests/Unit/Services/Example/ExampleServiceTest.php`

### Step 2: Copy Template
Use an existing test as a template (e.g., `AmountServiceTest.php`)

### Step 3: Test Each Public Method
For each public method in the service:
1. Test the happy path (success scenario)
2. Test edge cases (empty results, nulls)
3. Test error cases (not found, invalid data)
4. Test with various input combinations

### Step 4: Run and Verify
```bash
php artisan test tests/Unit/Services/Example/ExampleServiceTest.php
```

## Example: Testing a CRUD Service

```php
class ExampleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ExampleService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ExampleService();
    }

    // CREATE
    public function test_create_successfully_creates_record()
    {
        $data = ['name' => 'Test'];
        $result = $this->service->create($data);
        
        $this->assertInstanceOf(Example::class, $result);
        $this->assertDatabaseHas('examples', ['name' => 'Test']);
    }

    // READ
    public function test_get_by_id_returns_record_when_exists()
    {
        $example = Example::factory()->create();
        $result = $this->service->getById($example->id);
        
        $this->assertNotNull($result);
        $this->assertEquals($example->id, $result->id);
    }

    public function test_get_by_id_returns_null_when_not_exists()
    {
        $result = $this->service->getById(9999);
        $this->assertNull($result);
    }

    public function test_get_all_returns_all_records()
    {
        Example::factory()->count(5)->create();
        $result = $this->service->getAll();
        
        $this->assertCount(5, $result);
    }

    // UPDATE
    public function test_update_successfully_updates_record()
    {
        $example = Example::factory()->create(['name' => 'Old']);
        $result = $this->service->update($example->id, ['name' => 'New']);
        
        $this->assertTrue($result);
        $this->assertDatabaseHas('examples', ['id' => $example->id, 'name' => 'New']);
    }

    public function test_update_returns_false_when_not_found()
    {
        $result = $this->service->update(9999, ['name' => 'Test']);
        $this->assertFalse($result);
    }

    // DELETE
    public function test_delete_successfully_deletes_record()
    {
        $example = Example::factory()->create();
        $result = $this->service->delete($example->id);
        
        $this->assertTrue($result);
        $this->assertDatabaseMissing('examples', ['id' => $example->id]);
    }

    public function test_delete_returns_false_when_not_found()
    {
        $result = $this->service->delete(9999);
        $this->assertFalse($result);
    }
}
```

## Testing Services with Dependencies

When a service has dependencies, mock them in tests:

```php
use Mockery;

public function test_method_with_dependency()
{
    // Arrange
    $mockRepository = Mockery::mock(UserRepository::class);
    $mockRepository->shouldReceive('find')
        ->once()
        ->with(1)
        ->andReturn(User::factory()->make());
    
    $service = new UserService($mockRepository);
    
    // Act
    $result = $service->getUser(1);
    
    // Assert
    $this->assertInstanceOf(User::class, $result);
}
```

## Continuous Integration

These tests are designed to run in CI/CD pipelines. Ensure your pipeline:

1. Sets up the testing database
2. Runs migrations
3. Executes the test suite
4. Generates coverage reports

Example GitHub Actions workflow:
```yaml
- name: Run Tests
  run: php artisan test --parallel
```

## Maintenance

### Updating Tests When Services Change
When modifying a service:
1. Update corresponding tests
2. Add tests for new methods
3. Update tests for modified methods
4. Remove tests for deleted methods
5. Run full test suite to ensure nothing broke

### Reviewing Test Coverage
Periodically review test coverage to ensure:
- All service methods have tests
- All code paths are covered
- Edge cases are handled
- Error scenarios are tested

## Additional Resources

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Mockery Documentation](http://docs.mockery.io/)

## Questions or Issues?

If you encounter issues or have questions about the test suite:
1. Check this documentation
2. Review existing test examples
3. Consult the team's testing guidelines
4. Ask in the development channel
