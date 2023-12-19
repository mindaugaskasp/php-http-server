CURRENT_DIR = $(shell pwd)
CS_FIXER = $(CURRENT_DIR)/vendor/bin/php-cs-fixer
CS_FIXER_CONFIG = $(CURRENT_DIR)/.php-cs-fixer.dist.php

fix:
	php $(CS_FIXER) fix --config=$(CS_FIXER_CONFIG) --verbose --show-progress=dots

check:
	php $(CS_FIXER) fix --config=$(CS_FIXER_CONFIG) -vv --dry-run