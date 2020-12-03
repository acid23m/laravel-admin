#
# Project tools.
# Documentation for makefile:
# - https://www.gnu.org/software/make/manual/make.html
# - http://linux.yaroslavl.ru/docs/prog/gnu_make_3-79_russian_manual.html
#
# Usage:
# make - shows help with available commands
# make [command] - runs specific command
#

SHELL=/bin/bash

PROJECT_DIR=${PWD}
NODE_V=alpine
COMPOSER_V=2
PHP_V=8.0

DEFAULT_GOAL := help
.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)


define NODE_DEPS=
npm install
endef

define NODE_DEPS_DOCKER=
docker run --rm \
	-v $(PROJECT_DIR):/app \
	-w /app \
	-u $(id -u):$(id -g) \
	node:$(NODE_V) \
		sh -c "apk add --no-cache git && npm install"
endef

define COMPOSER_DEPS=
composer self-update --clean-backups
composer update --prefer-dist --ignore-platform-reqs --optimize-autoloader -v
composer dump-autoload --optimize -v
composer clear-cache
endef

define COMPOSER_DEPS_DOCKER=
docker run -i --rm \
	-v $(PROJECT_DIR):/app \
	-w /app \
	-u $(id -u):$(id -g) \
	composer:$(COMPOSER_V) update --prefer-dist --ignore-platform-reqs --optimize-autoloader -vvv
docker run -i --rm \
	-v $(PROJECT_DIR):/app \
	-w /app \
	-u $(id -u):$(id -g) \
	composer:$(COMPOSER_V) dump-autoload --optimize -vvv
endef


##@ Dependencies


$(APP_DIR)/vendor: $(APP_DIR)/composer.json
	-@$(COMPOSER_DEPS)
	-@$(COMPOSER_DEPS_DOCKER)


.PHONY: composer-native
composer-native: ## Installs/upgrades backend dependencies.
	@$(COMPOSER_DEPS)

.PHONY: composer-docker
composer-docker: ## Installs/upgrades backend dependencies.
	@$(COMPOSER_DEPS_DOCKER)

.PHONY: composer
composer: ## Installs/upgrades backend dependencies.
	-@$(MAKE) composer-native
	-@$(MAKE) composer-docker


##@ Assets


$(PROJECT_DIR)/node_modules: $(PROJECT_DIR)/package.json
	-@$(NODE_DEPS)
	-@$(NODE_DEPS_DOCKER)


.PHONY: node-native
node-native: ## Installs/upgrades frontend dependencies.
	@$(NODE_DEPS)

.PHONY: node-docker
node-docker: ## Installs/upgrades frontend dependencies.
	@$(NODE_DEPS_DOCKER)

.PHONY: node
node: ## Installs/upgrades frontend dependencies.
	-@$(MAKE) node-native
	-@$(MAKE) node-docker


.PHONY: build-native
build-native: $(PROJECT_DIR)/node_modules ## Builds the project frontend.
	@npm run prod

.PHONY: build-docker
build-docker: $(PROJECT_DIR)/node_modules ## Builds the project frontend.
	@docker run --rm \
		-v $(PROJECT_DIR):/app \
		-w /app \
		-u $(id -u):$(id -g) \
		node:$(NODE_V) \
			npm run prod

.PHONY: build
build: $(PROJECT_DIR)/node_modules ## Builds the project frontend.
	-@$(MAKE) build-native
	-@$(MAKE) build-docker


##@ Test


.PHONY: test-native
test-native:
	@php vendor/bin/phpunit --colors=always

.PHONY: test-docker
test-docker:
	@docker run --rm \
		-v $(PROJECT_DIR):/app \
		-w /app \
		-u $(id -u):$(id -g) \
		php:$(PHP_V)-cli-alpine \
			php vendor/bin/phpunit --colors=always

.PHONY: test
test:
	-@$(MAKE) test-native
	-@$(MAKE) test-docker

.PHONY: test-custom-docker
test-custom-docker:
	@docker run -it --rm \
		-v $(PROJECT_DIR):/app \
		-w /app \
		-u $(id -u):$(id -g) \
		php:$(PHP_V)-cli-alpine \
			bash
