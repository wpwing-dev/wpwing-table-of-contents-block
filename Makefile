PLUGIN_SLUG = wpwing-table-of-contents-block
SRC_DIR     = src
DIST_DIR    = dist
BUILD_DIR   = $(DIST_DIR)/$(PLUGIN_SLUG)
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

dist: clean-build build ## Build the distributable plugin zip into dist/
	mkdir -p $(BUILD_DIR)
	rsync -r --exclude-from=.distignore $(SRC_DIR)/ $(BUILD_DIR)/
	cd $(DIST_DIR) && zip -qr $(PLUGIN_SLUG).zip $(PLUGIN_SLUG)/
	rm -rf $(BUILD_DIR)
	unzip -t $(DIST_DIR)/$(PLUGIN_SLUG).zip > /dev/null
	@echo "Built: $(DIST_DIR)/$(PLUGIN_SLUG).zip"
.PHONY: dist

clean-build: ## Remove the staging build dir
	rm -rf $(BUILD_DIR)
.PHONY: clean-build

clean: ## Remove the entire dist directory
	rm -rf $(DIST_DIR)
.PHONY: clean

version: ## Bump version strings - usage: make version V=1.11.0
	@[ -n "$(V)" ] || (echo "Usage: make version V=1.11.0" && exit 1)
	sed -i "s/\\(Version:[[:space:]]*\\)[0-9][0-9.]*/\\1$(V)/" $(SRC_DIR)/$(PLUGIN_SLUG).php
	sed -i "s/\\(Stable tag:[[:space:]]*\\)[0-9][0-9.]*/\\1$(V)/" $(SRC_DIR)/readme.txt
	sed -i "s/\"version\": \".*\"/\"version\": \"$(V)\"/" package.json
	sed -i "s/\"version\": \".*\"/\"version\": \"$(V)\"/" $(SRC_DIR)/assets/src/block.json
	@echo "Version bumped to $(V)"
.PHONY: version

check: ## Verify version strings are consistent across all files
	@V=$$(grep 'Version:' $(SRC_DIR)/$(PLUGIN_SLUG).php | grep -o '[0-9]\+\.[0-9]\+\.[0-9]\+' | head -1); \
	echo "Checking version $$V ..."; \
	errors=0; \
	grep -q "\"version\": \"$$V\"" package.json                   || { echo "  FAIL: version in package.json"; errors=1; }; \
	grep -q "\"version\": \"$$V\"" $(SRC_DIR)/assets/src/block.json || { echo "  FAIL: version in block.json"; errors=1; }; \
	grep -qP "Stable tag:\\s+$$V" $(SRC_DIR)/readme.txt             || { echo "  FAIL: Stable tag in readme.txt"; errors=1; }; \
	[ $$errors -eq 0 ] && echo "  All version strings match $$V" || exit 1
.PHONY: check

screenshots: ## Capture WordPress.org listing screenshots into wporg-assets/ and update readme.txt captions
	$(MAKE) dev
# Listing assets must show only this plugin: other plugins add admin notices and rival blocks
# to the inserter. The import shot reads the seeded lwptoc_* options, not the LuckyWP plugin.
	docker compose --profile dev run --rm wpcli wp option update lwptoc_general '{"min":2,"depth":4,"hierarchical":true,"numeration":"decimalnested","title":"Contents","toggle":true,"smoothScroll":true,"smoothScrollOffset":40}' --format=json --allow-root
	docker compose --profile dev run --rm wpcli wp option update lwptoc_autoInsert '{"enable":true,"position":"beforefirstheading","postTypes":["post"]}' --format=json --allow-root
	@OTHERS=$$(docker compose --profile dev run --rm -T wpcli wp plugin list --status=active --field=name --allow-root 2>/dev/null | tr -d '\r' | grep -v '^wpwing-table-of-contents-block$$' | tr '\n' ' '); \
	[ -z "$$OTHERS" ] || echo "Deactivating for capture: $$OTHERS"; \
	[ -z "$$OTHERS" ] || docker compose --profile dev run --rm -T wpcli wp plugin deactivate $$OTHERS --allow-root >/dev/null 2>&1; \
	docker run --rm --network host --add-host=toc.local:127.0.0.3 -v $(CURDIR):/work -w /work $(PLAYWRIGHT_IMAGE) node tools/screenshots.js; status=$$?; \
	[ -z "$$OTHERS" ] || docker compose --profile dev run --rm -T wpcli wp plugin activate $$OTHERS --allow-root >/dev/null 2>&1; \
	docker run --rm -v $(CURDIR):/work -w /work $(PLAYWRIGHT_IMAGE) chown -R $(shell id -u):$(shell id -g) wporg-assets $(SRC_DIR)/readme.txt; \
	docker compose --profile dev run --rm wpcli wp option delete lwptoc_general lwptoc_autoInsert --allow-root; \
	exit $$status
.PHONY: screenshots