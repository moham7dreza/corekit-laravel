# Makefile for Avinar Laravel + React Project

# Colors
GREEN := \033[0;32m
NC := \033[0m

# Environment
APP_NAME := avinar-laravel
CONTAINER_PHP := app
CONTAINER_NODE := node

# Default command
.DEFAULT_GOAL := help

# -------------------------------
# Help
# -------------------------------
help:
	@echo -e "${GREEN}Available commands:${NC}"
	@echo "  make install            Install PHP and Node dependencies"
	@echo "  make migrate            Run Laravel migrations"
	@echo "  make seed               Run Laravel seeders"
	@echo "  make dev                Start Laravel dev server and React dev server"
	@echo "  make build              Build React frontend for production"
	@echo "  make test               Run PHP & JS tests"
	@echo "  make artisan            Run artisan command inside container"
	@echo "  make npm                Run npm command inside container"
	@echo "  make composer           Run composer command inside container"

# -------------------------------
# Install
# -------------------------------
install:
	@echo -e "${GREEN}Installing PHP dependencies...${NC}"
	composer install
	@echo -e "${GREEN}Installing Node dependencies...${NC}"
	npm install

# -------------------------------
# Laravel Artisan commands
# -------------------------------
artisan:
	php artisan $(filter-out $@,$(MAKECMDGOALS))

migrate:
	@echo -e "${GREEN}Running migrations...${NC}"
	php artisan migrate

seed:
	@echo -e "${GREEN}Seeding database...${NC}"
	php artisan db:seed

# -------------------------------
# React / Frontend
# -------------------------------
dev:
	@echo -e "${GREEN}Starting development servers...${NC}"
	@echo -e "Laravel: http://localhost:8000"
	@echo -e "React: http://localhost:3000"
	@npm run dev

build:
	@echo -e "${GREEN}Building frontend for production...${NC}"
	@npm run build

# -------------------------------
# Testing
# -------------------------------
test:
	@echo -e "${GREEN}Running PHP tests...${NC}"
	php artisan test
	@echo -e "${GREEN}Running JS tests...${NC}"
	npm run test

# -------------------------------
# Run composer / npm commands
# -------------------------------
composer:
	composer $(filter-out $@,$(MAKECMDGOALS))

npm:
	npm $(filter-out $@,$(MAKECMDGOALS))

# -------------------------------
# Clean / cache
# -------------------------------
clear:
	@echo -e "${GREEN}Clearing caches...${NC}"
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear

rector:
	vendor/bin/rector process
rector-test:
	vendor/bin/rector process --dry-run
