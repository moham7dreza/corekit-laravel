# Contributing

We welcome contributions to CoreKit Laravel Template!

## Getting Started

1. Fork the repository
2. Clone your fork: `git clone https://hamgit.ir/mohamadreza_rezaei/corekit-laravel.git`
3. Create a feature branch: `git checkout -b feature/your-feature`

## Development Setup

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Setup database
php artisan migrate --seed

# Start development server
php artisan serve