# Variables
DOCKER_COMPOSE = docker compose
CONTAINER_PHP = app
CONTAINER_DB = database
EXEC=php bin/console

# PROJECT_NAME
PROJECT_NAME?=BlackBox

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

init: ## Install dependencies and build Docker images
	$(DOCKER_COMPOSE) build --no-cache

up: ## Start local project
	$(DOCKER_COMPOSE) up -d

up-build: init up ## Init build + up

down: ## Stop containers and remove volumes
	$(DOCKER_COMPOSE) down --remove-orphans

restart: down up ## Down containers & volumes then up containers again

stop: ## Stop project
	$(DOCKER_COMPOSE) stop

logs-app: ## Show logs for the app container
	$(DOCKER_COMPOSE) logs -f $(CONTAINER_PHP)

logs-db: ## Show logs for the database container
	$(DOCKER_COMPOSE) logs -f $(CONTAINER_DB)

migration: ## Create a new migration
	$(EXEC) make:migration

migrate: ## Run database migrations
	$(EXEC) doctrine:migrations:migrate

db-diff: ## Generate a migration by comparing your current database to your mapping information
	$(EXEC) doctrine:migrations:diff

create-fixture: ## Create fixture file
	$(EXEC) make:fixture

seed: ## Load database fixtures
	$(EXEC) doctrine:fixtures:load

clear: ## Clear the cache
	$(EXEC) cache:clear

entity: ## Create entity
	$(EXEC) make:entity

crud: ## Make crud
	$(EXEC) make:crud

admin-crud: ## Make admin crud
	$(EXEC) make:admin:crud

assets-install: ## Install bundle's web assets under a public directory
	$(EXEC) assets:install

routes: ## List all routes
	$(EXEC) debug:router

start: ## Start the Symfony server
	symfony server:start

messenger: ##Consuming Messages (Running the Worker)
	$(EXEC) messenger:consume async -vv

.PHONY: help init up up-build down restart stop logs-app logs-db migrate db-diff create-fixtures seed clear cache-warmup assets-install composer-install routes start entity crud admin-crud migration messenger
