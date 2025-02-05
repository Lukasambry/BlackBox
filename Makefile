# Variables
DOCKER_COMPOSE = docker compose
CONTAINER_PHP = app
CONTAINER_DB = database
EXEC=php bin/console

# PROJECT_NAME
PROJECT_NAME?=BlackBox

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

init: ## Init project by installing dependencies, starting containers, running migrations and seeding the database
	make up && make start && make migrate && make seed && make assets-install && make messenger

up: ## Start local project
	$(DOCKER_COMPOSE) up -d

down: ## Stop containers and remove volumes
	 symfony local:server:stop && $(DOCKER_COMPOSE) down --remove-orphans --volumes

build: ## Build project
	$(DOCKER_COMPOSE) build

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
	$(EXEC) doctrine:migrations:migrate -n ## -n is for automatic yes

db-diff: ## Generate a migration by comparing your current database to your mapping information
	$(EXEC) doctrine:migrations:diff

create-fixture: ## Create fixture file
	$(EXEC) make:fixture

seed: ## Load database fixtures
	$(EXEC) doctrine:fixtures:load -n ## -n is for automatic yes

clear: ## Clear the cache
	$(EXEC) cache:clear

entity: ## Create entity
	$(EXEC) make:entity

controller: ## Create controller
	$(EXEC) make:controller

crud: ## Make crud
	$(EXEC) make:crud

admin-crud: ## Make admin crud
	$(EXEC) make:admin:crud

assets-install: ## Install bundle's web assets under a public directory
	$(EXEC) assets:install

routes: ## List all routes
	$(EXEC) debug:router

test: ## Run tests
	$(EXEC) phpunit --testdox

start: ## Start the Symfony server
	symfony server:start -d

messenger: ##Consuming Messages (Running the Worker)
	$(EXEC) messenger:consume async -vv

lint: ## Lint the code
	vendor/bin/phpcs -n src/ ##Ignore warnings, only show errors

lint-fix: ## Lint the code and fix
	vendor/bin/phpcbf src/


.PHONY: help init up down build restart stop lint lint-fix logs-app logs-db migrate db-diff create-fixtures seed clear cache-warmup assets-install composer-install routes start entity crud admin-crud migration messenger