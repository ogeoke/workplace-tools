# Testing Strategy - Workplace Operating System

## Testing Approach

### Test Types

1. **Unit Tests** - Test individual functions/methods
2. **Feature Tests** - Test complete features/workflows
3. **Integration Tests** - Test module interactions
4. **API Tests** - Test API endpoints
5. **Performance Tests** - Test response times
6. **Security Tests** - Test authorization/authentication

### Coverage Target: 80%+

## Directory Structure

```
tests/
├── Feature/
│   ├── Authentication/
│   │   ├── LoginTest.php
│   │   ├── RegisterTest.php
│   │   └── TwoFactorTest.php
│   ├── Communication/
│   │   ├── ChannelTest.php
│   │   ├── MessageTest.php
│   │   └── DirectMessageTest.php
│   ├── Projects/
│   │   ├── ProjectTest.php
│   │   ├── TaskTest.php
│   │   └── SprintTest.php
│   ├── Operations/
│   │   ├── CompanyTest.php
│   │   ├── InvoiceTest.php
│   │   └── ClientTest.php
│   └── Api/
│       └── ApiAuthTest.php
├── Unit/
│   ├── Services/
│   │   ├── InvoiceServiceTest.php
│   │   ├── TaskServiceTest.php
│   │   └── NotificationServiceTest.php
│   ├── Models/
│   │   ├── UserTest.php
│   │   ├── InvoiceTest.php
│   │   └── TaskTest.php
│   ├── Repositories/
│   │   └── InvoiceRepositoryTest.php
│   └── Jobs/
│       └── SendWhatsAppMessageJobTest.php
├── Integration/
│   ├── WhatsApp/
│   │   └── GreenApiIntegrationTest.php
│   ├── Notifications/
│   │   └── NotificationServiceIntegrationTest.php
│   └── Realtime/
│       └── BroadcastingTest.php
└── TestCase.php
```

## Sample Tests

### Feature Test

```php
namespace Tests\Feature\Authentication;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'invalid'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
```

### Unit Test

```php
namespace Tests\Unit\Services;

use App\Models\Invoice;
use App\Services\Operations\InvoiceService;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    private InvoiceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(InvoiceService::class);
    }

    public function test_can_create_invoice(): void
    {
        $data = [
            'invoice_number' => 'INV-001',
            'amount' => 1000.00,
            'due_date' => now()->addDays(30)
        ];

        $invoice = $this->service->create($data);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('INV-001', $invoice->invoice_number);
    }

    public function test_can_update_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $newAmount = 2000.00;

        $updated = $this->service->update($invoice, ['amount' => $newAmount]);

        $this->assertEquals($newAmount, $updated->amount);
    }
}
```

### API Test

```php
namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Project;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_can_list_projects(): void
    {
        Project::factory(5)->for($this->user)->create();

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_can_create_project(): void
    {
        $data = [
            'name' => 'New Project',
            'key' => 'NP',
            'description' => 'Test project'
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/v1/projects', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'New Project');
    }
}
```

### Integration Test

```php
namespace Tests\Integration\WhatsApp;

use App\Jobs\Notifications\SendWhatsAppMessageJob;
use App\Models\User;
use App\Services\WhatsApp\GreenApiService;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GreenApiIntegrationTest extends TestCase
{
    public function test_whatsapp_message_is_sent(): void
    {
        Queue::fake();

        $user = User::factory()->create(['whatsapp_number' => '+1234567890']);

        SendWhatsAppMessageJob::dispatch($user, 'Hello World');

        Queue::assertPushed(SendWhatsAppMessageJob::class);
    }

    public function test_failed_whatsapp_message_is_retried(): void
    {
        $this->expectsJobs(SendWhatsAppMessageJob::class);

        // Test retry logic
    }
}
```

## Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Authentication/LoginTest.php

# Run specific test method
php artisan test tests/Feature/Authentication/LoginTest.php --filter=test_user_can_login

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run tests with verbosity
php artisan test --verbose

# Parallel testing (requires parallel-testing package)
php artisan test --parallel
```

## Test Database

### Setup

```php
// tests/TestCase.php
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup test data
    }
}
```

## Factories

```php
// database/factories/InvoiceFactory.php
namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'invoice_number' => 'INV-' . $this->faker->numerify('######'),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'due_date' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'status' => 'draft'
        ];
    }
}
```

## Seeding for Tests

```php
// tests/Feature/MyTest.php
public function test_something(): void
{
    // Create test data using factories
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    $tasks = Task::factory(5)->for($project)->create();

    // Run test
    $this->actingAs($user)
        ->get('/projects/' . $project->id)
        ->assertSee('Tasks')
        ->assertCount(5);
}
```

## Continuous Integration

### GitHub Actions Example

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: test_db
          MYSQL_PASSWORD: password
          MYSQL_ROOT_PASSWORD: password
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3
      redis:
        image: redis
        options: >-
          --health-cmd "redis-cli ping"
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5

    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
          
      - name: Install dependencies
        run: composer install
        
      - name: Run tests
        run: php artisan test --coverage
        
      - name: Upload coverage
        uses: codecov/codecov-action@v2
```

## Test Naming Conventions

```php
// Good test names
public function test_user_can_login_with_valid_credentials(): void
public function test_user_cannot_login_with_invalid_password(): void
public function test_invoice_status_is_marked_as_overdue_after_due_date(): void
public function test_only_authenticated_users_can_create_projects(): void
public function test_whatsapp_message_is_sent_to_user(): void

// Bad test names
public function test(): void
public function testLogin(): void
public function test1(): void
```

## Coverage Report

```bash
# Generate HTML coverage report
php artisan test --coverage --coverage-html=coverage

# View report
open coverage/index.html
```

## Best Practices

1. **Arrange-Act-Assert** pattern
2. **One assertion per test** (or related assertions)
3. **Clear, descriptive test names**
4. **Use factories for test data**
5. **Mock external services**
6. **Test edge cases**
7. **Avoid test interdependencies**
8. **Keep tests fast**
9. **Test behavior, not implementation**
10. **Maintain high coverage** (80%+)