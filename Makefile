.PHONY: help setup start stop restart logs deploy-local deploy-prod ollama-pull db-migrate test

help:
	@echo "MyDigitalShadow - OSINT Platform"
	@echo ""
	@echo "Available commands:"
	@echo "  make setup          - Initial setup"
	@echo "  make start          - Start all services"
	@echo "  make stop           - Stop all services"
	@echo "  make restart        - Restart all services"
	@echo "  make logs           - Show logs"
	@echo "  make deploy-local   - Deploy on local Docker"
	@echo "  make deploy-prod    - Deploy on Hostinger VPS"
	@echo "  make ollama-pull    - Download AI model"
	@echo "  make db-migrate     - Run database migrations"
	@echo "  make test           - Run test suite"
	@echo "  make ssl-init       - Initialize SSL with Certbot"

setup:
	cp .env.example .env
	docker compose build

start:
	docker compose up -d
	@echo "Services started. Check status with: make logs"

stop:
	docker compose down

restart:
	docker compose restart

logs:
	docker compose logs -f --tail=50

deploy-local:
	make setup
	make db-migrate
	make ollama-pull
	make start

deploy-prod:
	ssh $(VPS_USER)@$(VPS_HOST) "cd /var/www/mydigitalshadow && git pull origin main"
	ssh $(VPS_USER)@$(VPS_HOST) "cd /var/www/mydigitalshadow && docker compose down"
	ssh $(VPS_USER)@$(VPS_HOST) "cd /var/www/mydigitalshadow && docker compose build"
	ssh $(VPS_USER)@$(VPS_HOST) "cd /var/www/mydigitalshadow && docker compose up -d"

ollama-pull:
	docker compose exec ollama ollama pull mistral:7b

db-migrate:
	docker compose exec laravel php artisan migrate --force
	docker compose exec laravel php artisan db:seed --force

test:
	docker compose exec laravel php artisan test
	docker compose exec fastapi pytest app/tests/

ssl-init:
	docker compose run --rm certbot certonly --webroot --webroot-path=/var/www/html -d mydigitalshadow.in -d www.mydigitalshadow.in --agree-tos --non-interactive --email admin@mydigitalshadow.in

backup:
	@mkdir -p backups/$$(date +%Y%m%d)
	docker compose exec postgres pg_dump -U mds_user mydigitalshadow > backups/$$(date +%Y%m%d)/db.sql
	tar -czf backups/$$(date +%Y%m%d)/storage.tar.gz -C ./backend/laravel storage

monitor:
	@docker compose ps
	@echo ""
	@docker stats --no-stream
