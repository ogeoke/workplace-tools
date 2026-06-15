# Workplace Operating System - Enterprise Architecture

## System Overview

The Workplace Operating System is a comprehensive, enterprise-grade platform combining:
- Slack-style communication
- Jira-style project management
- ClickUp/Asana task management
- Internal operations management
- WhatsApp integration via Green API
- Real-time collaboration with Laravel Reverb
- Multi-tenant architecture
- Enterprise-grade security with RBAC

## Technology Stack

### Backend
- **Framework**: PHP Laravel 11
- **Architecture**: Service Layer + Repository Pattern + Event-Driven
- **Queue**: Laravel Queue (Redis/Database)
- **Real-time**: Laravel Reverb (Pusher fallback)
- **Authentication**: Laravel Jetstream + Sanctum + 2FA
- **Admin Panel**: Filament v3
- **Permissions**: Spatie Laravel Permission

### Database
- **Primary**: MySQL 8.0+
- **Features**: Soft deletes, UUID support, audit fields
- **Caching**: Redis
- **Session**: Database/Redis

### Frontend
- **View Engine**: Laravel Blade + Livewire 3
- **Interactivity**: Alpine.js 3
- **Styling**: Tailwind CSS 3
- **Alternative**: Inertia.js + Vue 3

### External Services
- **WhatsApp**: Green API
- **Real-time**: Laravel Reverb or Pusher
- **Storage**: Local or AWS S3
- **Email**: SMTP compatible

### Hosting
- **Production**: Hostinger VPS
- **Alternative**: Hostinger Shared Hosting
- **Containerization**: Docker support

## Core Modules

### 1. Communication Module
- Public/Private/Team/Department/Project Channels
- Direct Messaging (1-to-1 & Group)
- Threads & Replies
- Reactions & Emoji
- File Attachments & Preview
- Voice Notes
- Real-time Presence & Typing Indicators
- Message Search & History
- Mentions & Tags

### 2. Project Management Module
- Projects with multiple views (Kanban, Sprint, Timeline, Calendar)
- Epics & User Stories
- Tasks & Subtasks
- Sprint Planning & Management
- Board Views with Drag-and-Drop
- Time Tracking & Estimation
- Dependencies & Blockers
- Activity History & Comments

### 3. Task Management Module
- Task Assignment & Delegation
- Priority Levels (Lowest to Highest)
- Due Dates & Reminders
- Status Tracking
- Activity Feed
- Comments & Attachments
- Custom Fields

### 4. Operations Management Module
- Company Management
- Client Management
- Invoice Management with Status Tracking
- SLA & Rent Management
- Asset Management
- Site Login Vault (Encrypted)
- Follow-up Tracking
- Diesel/Utility Tracking
- Operations Dashboard
- CSV/Excel Import-Export

### 5. Notification Engine
- In-App Notifications
- WhatsApp Notifications (Green API)
- Email Notifications
- Push Notifications (Ready)
- User Preferences
- Smart Delivery with Retry Logic
- Delivery Tracking

### 6. Admin Backend (Filament)
- Organization Management
- User Management
- Role & Permission Management
- System Settings
- Audit Logs
- Activity Logs
- WhatsApp Dashboard
- Analytics & Reports

## Security Architecture

### Authentication
- Email/Password with verification
- Multi-factor authentication (2FA)
- API token authentication (Sanctum)
- Session management
- Login attempt tracking
- Password reset with token validation

### Authorization
- Spatie Laravel Permission framework
- Role-Based Access Control (RBAC)
- 7 Default Roles:
  - Super Admin (Platform-wide)
  - Organization Admin (Organization-wide)
  - Department Head (Department-level)
  - Team Lead (Team-level)
  - Project Manager
  - Employee (Standard user)
  - Guest (Limited access)

### Data Security
- Encrypted credentials in Site Login Vault
- Audit logging for CRUD operations
- Activity tracking for sensitive access
- Organization-level data isolation
- Row-level security ready
- CSRF protection
- XSS prevention
- SQL injection prevention (Eloquent ORM)
- Rate limiting
- Input validation & sanitization

### Compliance
- GDPR ready (soft deletes, data export)
- SOC 2 ready
- Audit trail for all operations
- Encrypted sensitive data
- Secure file uploads
- Regular backup support

## Database Schema Highlights

### Core Tables
- organizations, users, departments, teams
- organization_users (pivot)
- roles, permissions (Spatie)

### Communication Tables
- channels, channel_members, messages, message_reactions
- message_attachments, direct_messages, thread_replies

### Project Management Tables
- projects, epics, tasks, subtasks, sprints
- task_assignees, task_comments, worklogs
- task_dependencies, boards

### Operations Tables
- companies, clients, invoices, sla_rent_records
- service_charges, diesel_records, company_assets
- site_login_vault, client_follow_ups, client_tracker

### Notification Tables
- notifications, user_notification_preferences
- notification_templates, whatsapp_logs
- activity_logs, audit_logs

## API Architecture

### Versioning
- RESTful API v1
- Pagination support
- Filtering & Sorting
- Search capabilities

### Authentication
- Sanctum token-based
- Rate limiting (60 req/min standard, 300 req/min premium)
- CORS enabled

### Response Format
```json
{
  "success": true,
  "data": {},
  "message": "Success",
  "errors": null
}
```

## Real-time Architecture

### Broadcasting Channels
- presence.channel.{id} - Channel presence
- presence.team.{id} - Team presence
- private.user.{id} - Private notifications
- private.organization.{id} - Organization updates
- public.announcements - Public announcements

### Events Broadcast
- Message created/edited/deleted
- Task status changes
- User presence changes
- Typing indicators
- Notification delivery
- Real-time data updates

## Queue Jobs

### Priority Jobs
- SendNotificationJob
- SendWhatsAppMessageJob
- RetryFailedWhatsAppJob
- SendEmailNotificationJob

### Background Jobs
- ProcessTaskReminder
- ProcessInvoiceReminder
- GenerateOperationsReport
- BackupDatabase
- CleanupLogs
- SyncRealTimeData
- UpdateActivityLog

## Scheduler Tasks

### Every 5 Minutes
- Retry failed WhatsApp messages

### Every 15 Minutes
- Process pending reminders

### Hourly
- Check due invoices
- Check SLA renewals

### Daily (9 AM)
- Generate daily summaries
- Process follow-up reminders

### Daily (2 AM)
- Backup database
- Archive old logs
- Clear temporary files

### Weekly
- Generate compliance reports
- Cleanup old activity logs

## Development Workflow

### Branch Strategy
- `main` - Production ready
- `develop` - Development branch
- `feature/*` - Feature branches
- `bugfix/*` - Bug fix branches
- `hotfix/*` - Production hotfixes

### Code Standards
- PSR-12 PHP coding standard
- Laravel best practices
- 80% test coverage target
- Static analysis with PHPStan
- Code formatting with PHP-CS-Fixer

## Performance Optimization

### Caching
- Query caching (Redis)
- Route caching
- Config caching
- View caching
- User session caching

### Database
- Proper indexing
- Query optimization
- Eager loading (prevent N+1)
- Connection pooling

### Frontend
- Asset minification
- Lazy loading
- Image optimization
- CDN support
- Gzip compression

## Monitoring & Logging

### Application Logging
- Laravel Log channel
- Activity logs (user actions)
- Audit logs (system changes)
- WhatsApp delivery tracking
- Queue monitoring

### Error Tracking
- Sentry integration ready
- Error notifications
- Stack trace logging

### Performance Monitoring
- Database query logging
- API response time tracking
- Queue job monitoring

## Phase Implementation Plan

**Phase 1: Core Infrastructure** (Week 1-2)
- Authentication & Authorization
- User Management
- Organization Setup
- Basic Database Schema

**Phase 2: Communication Module** (Week 3-5)
- Channels & Direct Messages
- Real-time Messaging
- File Attachments
- Search Functionality

**Phase 3: Project Management** (Week 6-8)
- Projects & Tasks
- Kanban Boards
- Sprint Management
- Time Tracking

**Phase 4: Operations Management** (Week 9-11)
- Companies & Clients
- Invoices
- SLA/Rent Management
- Assets

**Phase 5: Notifications & WhatsApp** (Week 12-13)
- In-App Notifications
- Green API Integration
- Email Notifications
- User Preferences

**Phase 6: Admin Backend** (Week 14-15)
- Filament Admin Panel
- Dashboard
- Audit Logs
- System Settings

**Phase 7: Real-time Features** (Week 16-17)
- Laravel Reverb Setup
- Broadcasting
- Presence Tracking
- Typing Indicators

**Phase 8: Testing & Optimization** (Week 18-19)
- Unit Tests
- Feature Tests
- Integration Tests
- Performance Optimization

**Phase 9: Deployment** (Week 20)
- Hostinger VPS Setup
- CI/CD Pipeline
- Production Hardening
- Monitoring Setup

**Phase 10: Documentation & Training** (Week 21)
- API Documentation
- User Guide
- Admin Guide
- Deployment Runbook

## AI-Ready Architecture

Future integration points prepared for OpenAI:
- `app/Services/AI/` namespace
- AI event listeners
- Model traits for AI data
- API endpoints ready

Future capabilities:
- AI meeting summaries
- AI task generation
- AI project planning
- AI productivity insights
- AI follow-up recommendations

## Git Repository

- **Repository**: github.com/ogeoke/workplace-tools
- **Default Branch**: main
- **Development Branch**: develop
- **Feature Branch**: feature/operations-management

## Next Steps

1. Review architecture documentation
2. Create database migrations
3. Generate Eloquent models
4. Build service layer
5. Create API controllers
6. Implement authentication
7. Set up real-time features
8. Build admin panel