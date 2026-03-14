.DEFAULT_GOAL := help

.PHONY: help build serve clean lint lint-fix analyse test test-html test-network check ci ci-act ci-act-dry

help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

build: ## Build static HTML site to dist/
	composer build

serve: ## Preview source files at localhost:8000
	composer serve

clean: ## Remove dist/ build output
	composer clean

lint: ## Check code style (PSR-12 via PHP_CodeSniffer)
	composer lint

lint-fix: ## Auto-fix code style issues
	composer lint:fix

analyse: ## Run static analysis (PHPStan level 5)
	composer analyse

test: ## Build site and run unit tests
	composer test

test-html: ## Validate generated HTML structure (requires prior build)
	composer test:html

test-network: ## Validate external URLs are reachable (requires network)
	composer test:network

check: ## Run all quality checks (lint, analyse, build, test, HTML validation)
	composer check

ci: ## Full CI — same as check + W3C HTML validator (requires Docker)
	composer check
	docker run --rm --platform linux/amd64 -v "$$(pwd)/dist":/dist ghcr.io/validator/validator:24.10.17 vnu --errors-only --skip-non-html /dist
	@echo "\n✅ All CI checks passed"

ci-act: ## Test CI workflow YAML in Docker via act (slow on Apple Silicon)
	@command -v act >/dev/null 2>&1 || { echo "act not installed. Run: brew install act"; exit 1; }
	act pull_request -W .github/workflows/ci.yml

ci-act-dry: ## Dry-run CI workflow via act
	@command -v act >/dev/null 2>&1 || { echo "act not installed. Run: brew install act"; exit 1; }
	act pull_request -W .github/workflows/ci.yml -n
