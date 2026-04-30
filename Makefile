.PHONY: help install build up down clean restart shell logs test migrate fresh seed pint cache-clear queue-restart tinker

help:
	@echo "Available commands:"
	@echo "  make install        First-time setup: build, install deps, migrate, seed"
	@echo "  make build          Build all containers"
	@echo "  make up             Start all services in background"
	@echo "  make down           Stop and remove containers (data preserved)"
	@echo "  make clean          Stop containers and DELETE all volumes (DESTRUCTIVE)"
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
	@echo "  make tinker         Open Laravel Tinker REPL inside the app container"

install:
	cp -n .env.example .env || true
	UID=$$(id -u) GID=$$(id -g) docker compose build
	UID=$$(id -u) GID=$$(id -g) docker compose up -d
	docker compose exec app composer install
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate --seed

build:
	UID=$$(id -u) GID=$$(id -g) docker compose build

up:
	UID=$$(id -u) GID=$$(id -g) docker compose up -d

down:
	docker compose down

clean:
	docker compose down -v

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

tinker:
	docker compose exec app php artisan tinker
