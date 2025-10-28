# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application using PHP 8.4+ with Pest for testing. The application runs in a Dockerized environment using Mbox (markshust/mbox-laravel), which provides nginx, PHP-FPM, MariaDB, Redis, Memcached, Mailpit, and Traefik reverse proxy with SSL support.

## Development Environment

### Starting/Stopping Services

- **Start all services**: `bin/start` - Starts Docker containers and clears Laravel caches
- **Stop all services**: `bin/stop` - Stops all Docker containers
- **Restart services**: `bin/restart` - Restarts all Docker containers

### Service URLs (when running via Docker)
- Application: http://localhost:8000
- Mailpit dashboard: http://localhost:8025
- Vite dev server: http://localhost:5173

### Container Access

- **Execute commands in PHP container**: `bin/phpfpm <command>`
  - Example: `bin/phpfpm php -v`
  - Example: `bin/phpfpm composer install`
- **Run artisan commands**: `bin/artisan <command>`
  - Example: `bin/artisan migrate`
  - Example: `bin/artisan make:model Product`

## Common Commands

### Testing

The project uses **Pest** (not PHPUnit) as the testing framework.

- **Run all tests**: `composer test` or `bin/phpfpm php artisan test`
- **Run a specific test file**: `bin/phpfpm php artisan test tests/Feature/ExampleTest.php`
- **Run a specific test**: `bin/phpfpm php artisan test --filter=test_name`
- **Run with coverage**: `bin/phpfpm php artisan test --coverage`

Test configuration is in `phpunit.xml`. Tests use SQLite in-memory database by default.

### Development

- **Start local dev environment** (non-Docker): `composer dev`
  - Runs Laravel dev server, queue listener, Pail logs, and Vite concurrently
  - Uses `concurrently` to run all services in parallel with colored output

- **Code style/linting**: `bin/phpfpm vendor/bin/pint`
  - Uses Laravel Pint for code formatting

- **Build assets**: `npm run build`
- **Watch assets**: `npm run dev` (or via Docker: access the node container)

### Database

- **Run migrations**: `bin/artisan migrate`
- **Rollback migrations**: `bin/artisan migrate:rollback`
- **Seed database**: `bin/artisan db:seed`
- **Fresh database with seeds**: `bin/artisan migrate:fresh --seed`

Database connection defaults to MariaDB (via `DB_CONNECTION=mariadb`). Service runs on port 3306.

### Composer

- **Install dependencies**: `bin/phpfpm composer install`
- **Update dependencies**: `bin/phpfpm composer update`
- **Initial setup**: `composer setup` - Runs full setup including migrations and asset builds

## Architecture

### Directory Structure

- `app/` - Application code
  - `app/Http/` - HTTP layer (Controllers, Middleware, Requests)
  - `app/Models/` - Eloquent models
  - `app/Providers/` - Service providers
- `routes/` - Route definitions
  - `routes/web.php` - Web routes
  - `routes/console.php` - Artisan commands
- `database/` - Migrations, seeders, and factories
- `resources/` - Views, JS, CSS
- `tests/` - Pest test files
  - `tests/Feature/` - Feature tests
  - `tests/Unit/` - Unit tests
- `config/` - Configuration files
- `docker/` - Docker configuration and images
- `bin/` - Helper scripts for container management

### Docker Services

The `compose.yaml` defines these services:

- **nginx**: Web server on port 8000
- **phpfpm**: PHP-FPM 8.2+ (custom build in `docker/images/phpfpm`)
- **mariadb**: Database on port 3306
- **node**: Node.js for Vite (custom build in `docker/images/node`)
- **memcached**: Caching on port 11211
- **redis**: Redis on port 6379
- **mailpit**: Email testing on ports 1025 (SMTP) and 8025 (UI)
- **traefik**: Reverse proxy with SSL support

All PHP commands should be executed via `bin/phpfpm` or `bin/artisan` to run inside the container.

### Testing Architecture

- Uses Pest PHP testing framework with Laravel plugin
- Test case base class: `Tests\TestCase`
- Feature tests automatically use `RefreshDatabase` trait (can be enabled in `tests/Pest.php`)
- Testing environment uses SQLite in-memory database (configured in `phpunit.xml`)

## Key Patterns

### Running Commands

When working with this codebase, remember:
- All PHP/artisan commands should use `bin/artisan` or `bin/phpfpm`
- All Composer commands should use `bin/phpfpm composer`
- Frontend development can use `npm` directly (or via node container)
- Never run `php artisan` directly - always use `bin/artisan`

### Environment Configuration

- Environment variables are in `.env` (copy from `.env.example` if needed)
- Docker-specific variables:
  - `DOCKER_TRAEFIK_IDENTIFIER` - Traefik routing identifier
  - `DOCKER_TRAEFIK_DOMAIN` - Domain for Traefik routing
  - `FORWARD_PORT_*` - Port forwarding configuration

### Mbox Environment

This project uses Mbox for Laravel, which provides a standardized Docker environment. Key characteristics:
- Services communicate via Docker network (use service names as hostnames)
- Database host should be `mariadb` (not `127.0.0.1`) when running in containers
- The `bin/` scripts intelligently detect if stdin is interactive and adjust Docker exec flags accordingly
