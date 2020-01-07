HOST_PORT = 8379
default: help

run_docker=docker-compose run --rm web

help: ## This help message
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' -e 's/:.*#/: #/' | column -t -s '##'

install: ## Build images
	@$(MAKE) setup-local-env
	@docker-compose build
	@$(run_docker) composer install

update: ## Update dependencies
	@$(run_docker) composer update

setup-local-env: ## Copy .env file and replace origin customer id
	cp -p .env.example .env

web-info:
	@echo
	@docker-compose ps | grep web > /dev/null \
		&& echo "App is running on http://localhost:$(HOST_PORT)" \
		|| echo "Your app is not running, use 'make install' or 'make up'"
	@echo

up: ## Start containers
	docker-compose up -d
	@$(MAKE) web-info

start: up ## Alias for up

down: ## Stop containers
	docker-compose stop

stop: down ## Alias for down

destroy: ## Destroy containers, images, network, volumes
	docker-compose down

restart: ## Restart containers
	@$(MAKE) down
	@$(MAKE) up

sh: ##Starts a bash shell in service container
	@$(run_docker) bash

ssh: ## Enters the web container
	@docker-compose exec web /bin/bash

osx-xdebug-ip: ## Xdebug setup for osx
	sudo ifconfig en0 alias 10.20.30.40 netmask 255.255.255.0

health: healthcheck ## Alias for healthcheck

healthcheck: ## Healthcheck
	open http://localhost:$(HOST_PORT)/healthcheck

logs: ## Tails web logs
	@docker-compose logs -f web

up-test: ## Start test containers
	@docker-compose up -d --build

stop-test: ## Stop test containers
	docker-compose stop

test: ## Run tests (in docker)
	@$(MAKE) up-test
	@docker-compose exec web /bin/bash -l -c "./vendor/bin/phpunit"

check: insights test ## Run insights and tests (in docker)

test-native: ## Run tests
	APP_ENV=testing ./vendor/bin/phpunit

check-native: insights-native test-native ## Run insights and tests

codestyle-check:
	@composer check-style

codestyle-fix:
	@composer fix-style

insights: ## Run PHP Insights (in docker)
	@docker-compose exec web /bin/bash -l -c "php artisan insights --no-interaction --min-quality=100 --min-architecture=100 --min-style=100"

insights-native: ## Run PHP Insights
	php artisan insights --no-interaction --min-quality=100 --min-architecture=100 --min-style=100
