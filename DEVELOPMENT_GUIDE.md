# Development Guide - Workplace Operating System

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Redis 6.0+
- Node.js 18+
- Git

### Local Setup

```bash
# Clone repository
git clone https://github.com/ogeoke/workplace-tools.git
cd workplace-tools

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate:fresh --seed

# Generate app key
php artisan app:name "Workplace Operating System"

# Start development servers
php artisan serve
npm run dev

# In another terminal, start queue worker
php artisan queue:work

# In another terminal, start Laravel Reverb
php artisan reverb:start
```

## Folder Structure Quick Reference

```
app/
├── Console/Commands/       # Artisan commands
├── Events/                 # Event classes
├── Http/
│   ├── Controllers/        # Route controllers
│   ├── Middleware/         # HTTP middleware
│   └── Requests/           # Form request validation
├── Jobs/                   # Queueable jobs
├── Listeners/              # Event listeners
├── Models/                 # Eloquent models
├── Notifications/          # Notification classes
├── Policies/               # Authorization policies
├── Repositories/           # Data access layer
├── Services/               # Business logic
└── Traits/                 # Reusable traits

database/
├── factories/              # Model factories
├── migrations/             # Database migrations
└── seeders/                # Database seeders

resources/
├── css/                    # CSS files
├── js/                     # JavaScript files
└── views/                  # Blade templates

routes/
├── api.php                 # API routes
├── web.php                 # Web routes
├── channels.php            # Broadcast channels
└── console.php             # Console commands

storage/
└── app/uploads/            # File uploads

tests/
├── Unit/                   # Unit tests
├── Feature/                # Feature tests
└── Integration/            # Integration tests
```

## Code Standards

### PSR-12 Compliance

```bash
# Check code style
php artisan pint --test

# Fix code style
php artisan pint
```

### Static Analysis

```bash
# Run PHPStan
phpstan analyse

# With strict level
phpstan analyse --level=9
```

### Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with coverage
php artisan test --coverage

# Run only unit tests
php artisan test --testsuite=Unit
```

## Creating a Feature

### 1. Create Migration

```bash
php artisan make:migration create_invoices_table --create=invoices
```

### 2. Create Model

```bash
php artisan make:model Models/Invoice -m
```

### 3. Create Service

```bash
# app/Services/Operations/InvoiceService.php
namespace App\Services\Operations;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;

class InvoiceService
{
    public function __construct(
        private InvoiceRepositoryInterface $repository
    ) {}

    public function create(array $data): Invoice
    {
        return $this->repository->create($data);
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        return $this->repository->update($invoice, $data);
    }
}
```

### 4. Create Controller

```bash
php artisan make:controller Api/V1/InvoiceController --model=Invoice
```

### 5. Create Event

```bash
php artisan make:event Operations/InvoiceCreated
```

### 6. Create Listener

```bash
php artisan make:listener Operations/SendInvoiceReminder --event=InvoiceCreated
```

### 7. Register in EventServiceProvider

```php
protected $listen = [
    InvoiceCreated::class => [
        SendInvoiceReminder::class,
    ],
];
```

### 8. Create Tests

```bash
php artisan make:test Feature/InvoiceTest
php artisan make:test Unit/Services/InvoiceServiceTest
```

## Authentication Flow

### Login
1. User submits credentials
2. Jetstream validates
3. Generate Sanctum token for API
4. Create session for web
5. Log login activity
6. Send notification

### API Authentication

```php
// In controller
public function store(Request $request)
{
    $request->user(); // Get authenticated user
}

// In routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/messages', [MessageController::class, 'store']);
});
```

## Permission & Role Management

### Create Role

```php
use Spatie\Permission\Models\Role;

$role = Role::create([
    'name' => 'project_manager',
    'guard_name' => 'web',
    'organization_id' => $organization->id
]);
```

### Create Permission

```php
use Spatie\Permission\Models\Permission;

$permission = Permission::create([
    'name' => 'create_projects',
    'guard_name' => 'web',
    'category' => 'projects'
]);
```

### Assign Permission to Role

```php
$role->givePermissionTo($permission);
```

### Assign Role to User

```php
$user->assignRole('project_manager');
```

### Check Permission in Controller

```php
if ($request->user()->can('create_projects')) {
    // Allow
}

// Or in blade
@can('create_projects')
    <button>Create Project</button>
@endcan
```

## Event & Listener Pattern

### Dispatch Event

```php
use App\Events\Projects\TaskCreated;

TaskCreated::dispatch($task);
```

### Listen to Event

```php
namespace App\Listeners\Projects;

class SendTaskNotification
{
    public function handle(TaskCreated $event)
    {
        // Send notification
        Notification::send($event->task->assignee, new TaskAssignedNotification($event->task));
    }
}
```

## Queue & Jobs

### Create Job

```bash
php artisan make:job Notifications/SendWhatsAppMessageJob
```

### Queue Job

```php
use App\Jobs\Notifications\SendWhatsAppMessageJob;

SendWhatsAppMessageJob::dispatch($message);

// With delay
SendWhatsAppMessageJob::dispatch($message)->delay(now()->addMinutes(5));

// To specific queue
SendWhatsAppMessageJob::dispatch($message)->onQueue('whatsapp');
```

### Process Queue

```bash
# Process jobs
php artisan queue:work

# With specific queue
php artisan queue:work redis --queue=whatsapp,notifications
```

## Database Migrations

### Create Migration

```bash
php artisan make:migration create_users_table --create=users
php artisan make:migration add_column_to_users_table --table=users
```

### Migration Structure

```php
namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(Organization::class)->constrained();
            $table->foreignIdFor(Client::class)->constrained();
            $table->decimal('amount', 15, 2);
            $table->date('due_date');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index(['organization_id', 'due_date']);
        });
    }

    public function rollback(): void
    {
        Schema::dropIfExists('invoices');
    }
};
```

## API Response Format

### Success Response

```php
return response()->json([
    'success' => true,
    'data' => $data,
    'message' => 'Operation successful',
    'meta' => [
        'total' => 100,
        'page' => 1,
        'per_page' => 20
    ]
]);
```

### Error Response

```php
return response()->json([
    'success' => false,
    'message' => 'Operation failed',
    'errors' => [
        'field' => ['Error message']
    ]
], 422);
```

## Real-time Broadcasting

### Create Channel

```php
// In routes/channels.php
Broadcast::private('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
```

### Broadcast from Model Event

```php
namespace App\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;

class Message extends Model
{
    use BroadcastsEvents;

    protected $dispatchesEvents = [
        'created' => MessageCreated::class,
    ];
}
```

### Listen in JavaScript

```javascript
echo.private(`user.${userId}`)
    .listen('MessageCreated', (e) => {
        console.log(e.message);
    });
```

## Common Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimize
php artisan optimize
php artisan config:cache

# Database
php artisan migrate
php artisan migrate:rollback
php artisan db:seed

# Queue
php artisan queue:work
php artisan queue:failed
php artisan queue:retry

# Admin
php artisan filament:install
php artisan filament:make-admin

# Testing
php artisan test
php artisan test --coverage
```

## Troubleshooting

### Queue Not Processing

```bash
# Check if worker is running
php artisan queue:work --verbose

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Database Connection Issues

```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Broadcast Not Working

```bash
# Start Reverb
php artisan reverb:start

# Check Echo configuration
console.log(window.Echo);
```

## Performance Tips

1. Use eager loading: `User::with('posts')->get()`
2. Use queues for heavy tasks
3. Cache frequently accessed data
4. Index database columns
5. Use pagination for large datasets
6. Minimize database queries
7. Use API versioning
8. Implement rate limiting