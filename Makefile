.PHONY: help build up down restart shell logs test migrate fresh seed pint cache-clear queue-restart

help:
	@echo "Available commands:"
	@echo "  make build          Build all containers"
	@echo "  make up             Start all services in background"
	@echo "  make down           Stop and remove containers"
	@echo "  make restart        Restart all services"
	@echo "  make shell          Open bash inside the app container"
	@echo "  make logs           Tail logs from all services"
	@echo "  make test           Run PHPUnit test suite"
	@echo "  make migrate        Run database migrations"
	@echo "  make fresh          Drop all tables and re-run migrations + seeders"
	@echo "  make seed           Run database seeders"
	@echo "  make pint           Run Laravel Pint code style fixer"
	@echo "  make cache-clear    Clear Laravel caches"
	@echo "  make queue-restart  Restart queue workers"

build:
	UID=$$(id -u) GID=$$(id -g) docker compose build

up:
	UID=$$(id -u) GID=$$(id -g) docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

shell:
	docker compose exec app bash

logs:
	docker compose logs -f

test:
	docker compose exec app php artisan test

migrate:
	docker compose exec app php artisan migrate

fresh:
	docker compose exec app php artisan migrate:fresh --seed

seed:
	docker compose exec app php artisan db:seed

pint:
	docker compose exec app ./vendor/bin/pint

cache-clear:
	docker compose exec app php artisan optimize:clear

queue-restart:
	docker compose exec app php artisan queue:restart
