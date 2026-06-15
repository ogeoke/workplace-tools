# Workplace Operating System

A comprehensive enterprise platform combining Slack, Jira, ClickUp, and internal operations management.

## Features

### 🗣️ Communication
- Real-time messaging
- Channels (public, private, team-based)
- Direct messaging
- Threads and reactions
- File sharing
- Voice notes
- Full message search

### 📋 Project Management
- Projects and epics
- Kanban boards
- Sprint planning
- Task management
- Time tracking
- Timeline and calendar views
- Dependencies and blockers

### 💼 Operations Management
- Company management
- Client management
- Invoice tracking
- SLA and rent management
- Asset management
- Site login vault (encrypted)
- Follow-up tracking
- Diesel/utility tracking

### 🔔 Notifications
- In-app notifications
- WhatsApp integration (Green API)
- Email notifications
- User preferences
- Smart delivery with retry

### 👥 Administration
- Multi-tenant support
- User management
- Role-based access control
- Audit logs
- Activity tracking
- System settings
- Analytics dashboard

### 🔐 Security
- Multi-factor authentication
- Encrypted sensitive data
- Audit trail
- Role-based permissions
- API token authentication
- Organization-level data isolation

## Tech Stack

- **Backend**: Laravel 11
- **Database**: MySQL 8.0+
- **Frontend**: Blade + Livewire + Alpine.js
- **Admin**: Filament
- **Real-time**: Laravel Reverb
- **Notifications**: WhatsApp (Green API)
- **Authentication**: Jetstream + Sanctum
- **Permissions**: Spatie Permission
- **Hosting**: Hostinger VPS/Shared

## Requirements

- PHP 8.2+
- MySQL 8.0+
- Redis 6.0+
- Node.js 18+
- Composer

## Installation

### Local Development

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

# Build assets
npm run build

# Start development server
php artisan serve

# In another terminal, start queue worker
php artisan queue:work

# In another terminal, start Reverb
php artisan reverb:start
```

### Production Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for detailed instructions.

## Documentation

- [Architecture](ARCHITECTURE.md) - System design and overview
- [Folder Structure](FOLDER_STRUCTURE.md) - Project organization
- [Database Schema](DATABASE_SCHEMA.md) - Complete database design
- [Development Guide](DEVELOPMENT_GUIDE.md) - Development best practices
- [Testing Strategy](TESTING_STRATEGY.md) - Testing approach
- [Deployment Guide](DEPLOYMENT.md) - Production deployment
- [Production Checklist](PRODUCTION_CHECKLIST.md) - Pre/post deployment checks

## API Documentation

API endpoints are documented in [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

## Environment Variables

Key environment variables:

```env
APP_URL=https://workplace.example.com
DB_HOST=localhost
DB_DATABASE=workplace_db
DB_USERNAME=workplace
DB_PASSWORD=password

BROADCAST_DRIVER=reverb
GREEN_API_INSTANCE_ID=your_id
GREEN_API_TOKEN=your_token

QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
```

See `.env.example` for all variables.

## Project Structure

```
├── app/
│   ├── Console/        # Artisan commands
│   ├── Events/         # Event classes
│   ├── Http/           # Controllers, middleware, requests
│   ├── Jobs/           # Queue jobs
│   ├── Listeners/      # Event listeners
│   ├── Models/         # Eloquent models
│   ├── Notifications/  # Notification classes
│   ├── Policies/       # Authorization policies
│   ├── Repositories/   # Data access layer
│   ├── Services/       # Business logic
│   └── Traits/         # Reusable traits
├── database/
│   ├── factories/      # Model factories
│   ├── migrations/     # Database migrations
│   └── seeders/        # Database seeders
├── resources/
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript
│   └── views/          # Blade templates
├── routes/
│   ├── api.php         # API routes
│   ├── web.php         # Web routes
│   └── channels.php    # Broadcast channels
├── storage/            # File storage
└── tests/              # Test suites
```

## Development

### Code Standards

```bash
# Check code style
php artisan pint --test

# Fix code style
php artisan pint

# Run static analysis
phpstan analyse
```

### Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test tests/Feature/AuthTest.php
```

### Database

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed database
php artisan db:seed
```

## Contributing

1. Create a feature branch (`git checkout -b feature/amazing-feature`)
2. Commit changes (`git commit -m 'Add amazing feature'`)
3. Push to branch (`git push origin feature/amazing-feature`)
4. Open a Pull Request

## Security

For security issues, please email security@workplace.example.com instead of using the issue tracker.

## License

MIT License - see LICENSE file for details

## Support

For support, email support@workplace.example.com or open an issue in the repository.

## Roadmap

- [ ] Phase 1: Core infrastructure
- [ ] Phase 2: Communication module
- [ ] Phase 3: Project management
- [ ] Phase 4: Operations management
- [ ] Phase 5: Notifications & WhatsApp
- [ ] Phase 6: Admin backend
- [ ] Phase 7: Real-time features
- [ ] Phase 8: Mobile app
- [ ] Phase 9: AI integration
- [ ] Phase 10: Advanced analytics

## Version History

### v1.0.0 (In Development)
- Initial release
- All core modules
- Full admin panel
- Real-time features
- WhatsApp integration

## Authors

- **ogeoke** - Initial development

## Acknowledgments

- Laravel framework
- Filament admin panel
- Spatie Laravel Permission
- Green API
