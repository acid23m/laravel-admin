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
NODEJS_V=13

DEFAULT_GOAL := help
.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)


define NODE_DEPS=
yarn install
endef

define NODE_DEPS_DOCKER=
docker run --rm \
	-v $(PROJECT_DIR):/app \
	-w /app \
	node:$(NODEJS_V)-alpine \
	sh -c "apk add --no-cache git && yarn install"
endef


$(PROJECT_DIR)/node_modules: $(PROJECT_DIR)/package.json
	-@$(NODE_DEPS)
	-@$(NODE_DEPS_DOCKER)


##@ Assets without Docker


.PHONY: node-native
node-native: ## Installs/upgrades frontend dependencies.
	@$(NODE_DEPS)

.PHONY: build-native
build-native: $(PROJECT_DIR)/node_modules ## Builds the project frontend.
	@npm run prod


##@ Assets with Docker


.PHONY: node-docker
node-docker: ## Installs/upgrades frontend dependencies.
	@$(NODE_DEPS_DOCKER)

.PHONY: build-docker
build-docker: $(PROJECT_DIR)/node_modules ## Builds the project frontend.
	@docker run --rm \
		-v $(PROJECT_DIR):/app \
		-w /app \
		node:${NODEJS_V}-alpine \
		npm run prod


##@ Assets


.PHONY: node
node: ## Installs/upgrades frontend dependencies.
	-@$(MAKE) node-native
	-@$(MAKE) node-docker

.PHONY: build
build: $(PROJECT_DIR)/node_modules ## Builds the project frontend.
	-@$(MAKE) build-native
	-@$(MAKE) build-docker
