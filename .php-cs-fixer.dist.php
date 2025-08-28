<?php

// .php-cs-fixer.dist.php
declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'tests/Application/var',
        'tests/Application/vendor',
        'vendor'
    ]);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'strict_comparison' => true,
        'strict_param' => true,
    ])
    ->setFinder($finder);

// phpstan.neon
includes:
    - phpstan-baseline.neon

parameters:
    level: 8
    reportUnmatchedIgnoredErrors: false
    
    paths:
        - src

    excludePaths:
        - src/DependencyInjection/Configuration.php
        - tests/Application/*

    ignoreErrors:
        - '#Access to an undefined property#'
        - '#Call to an undefined method#'

// .gitignore
###> symfony/framework-bundle ###
/.env.local
/.env.local.php
/.env.*.local
/config/secrets/prod/prod.decrypt.private.php
/public/bundles/
/var/
/vendor/
###< symfony/framework-bundle ###

###> phpunit/phpunit ###
/phpunit.xml
.phpunit.result.cache
###< phpunit/phpunit ###

###> IDE ###
/.idea/
/.vscode/
*.swp
*.swo
*~
###< IDE ###

###> composer ###
composer.lock
###< composer ###

# Makefile
.DEFAULT_GOAL := help

SYLIUS_VERSION ?= 2.0
SYMFONY_VERSION ?= 7.3
PHP_VERSION ?= 8.3

help: ## Show this help message
	@echo 'Usage: make [target] ...'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install dependencies
	composer install --no-interaction --prefer-dist

update: ## Update dependencies
	composer update --no-interaction --prefer-dist

test: ## Run tests
	vendor/bin/phpunit

test-unit: ## Run unit tests only
	vendor/bin/phpunit tests/Unit

test-functional: ## Run functional tests only
	vendor/bin/phpunit tests/Functional

cs-fix: ## Fix coding standards
	vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php

cs-check: ## Check coding standards
	vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run --diff

static-analysis: ## Run static analysis
	vendor/bin/phpstan analyze

quality: cs-check static-analysis test ## Run all quality checks

clean: ## Clean cache and logs
	rm -rf var/cache/*
	rm -rf var/logs/*

setup-test-app: ## Setup test application
	cd tests/Application && \
	composer install --no-interaction --prefer-dist && \
	APP_ENV=test bin/console doctrine:database:create --if-not-exists && \
	APP_ENV=test bin/console doctrine:schema:create && \
	APP_ENV=test bin/console sylius:fixtures:load default --no-interaction
