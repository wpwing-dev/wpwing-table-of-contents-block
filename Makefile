PLUGIN_SLUG = wpwing-table-of-contents-block
# Must match the @playwright/test version in package.json - browsers are pinned per release.
PLAYWRIGHT_IMAGE = mcr.microsoft.com/playwright:v1.62.1-noble

.DEFAULT_GOAL := help

help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'
.PHONY: help

build: ## Build the block assets
	npm run build
.PHONY: build


dev: ## Start local WordPress and activate the plugin
	docker compose --profile dev up -d --wait db wordpress
	$(MAKE) build
	docker compose --profile dev run --rm wpcli
	docker compose --profile dev up -d caddy
	@echo ""
	@echo "Site:  https://toc.local"
	@echo "Admin: https://toc.local/wp-admin  (admin / password)"
	@echo ""
	@echo "First time? Run: make caddy-trust"
.PHONY: dev

caddy-trust: ## Trust Caddy's local CA (run once per machine, requires sudo)
	@echo "Waiting for Caddy to generate its CA..."
	@sleep 3
	docker compose --profile dev cp caddy:/data/caddy/pki/authorities/local/root.crt /tmp/caddy-root.crt
	sudo cp /tmp/caddy-root.crt /usr/local/share/ca-certificates/caddy-toc.crt
	sudo update-ca-certificates
	@echo "Done. Restart your browser."
.PHONY: caddy-trust

dev-stop: ## Stop the local WordPress stack
	docker compose --profile dev down
.PHONY: dev-stop

env-reset: ## Stop the stack and remove its Docker volumes
	docker compose --profile dev down -v
.PHONY: env-reset

lint-js: ## Lint JavaScript
	npm run lint:js
.PHONY: lint-js

lint-css: ## Lint CSS and SCSS
	npm run lint:css
.PHONY: lint-css

format: ## Format source files
	npm run format
.PHONY: format

dist: ## Build the distributable plugin zip
	npm run dist
.PHONY: dist

screenshots: ## Capture WordPress.org listing screenshots into wporg-assets/ and update readme.txt captions
	$(MAKE) dev
	docker compose --profile dev run --rm wpcli wp option update lwptoc_general '{"min":2,"depth":4,"hierarchical":true,"numeration":"decimalnested","title":"Contents","toggle":true,"smoothScroll":true,"smoothScrollOffset":40}' --format=json --allow-root
	docker compose --profile dev run --rm wpcli wp option update lwptoc_autoInsert '{"enable":true,"position":"beforefirstheading","postTypes":["post"]}' --format=json --allow-root
	docker run --rm --network host --add-host=toc.local:127.0.0.4 -v $(CURDIR):/work -w /work $(PLAYWRIGHT_IMAGE) node tools/screenshots.js; status=$$?; \
	docker run --rm -v $(CURDIR):/work -w /work $(PLAYWRIGHT_IMAGE) chown -R $(shell id -u):$(shell id -g) wporg-assets readme.txt; \
	docker compose --profile dev run --rm wpcli wp option delete lwptoc_general lwptoc_autoInsert --allow-root; \
	exit $$status
.PHONY: screenshots