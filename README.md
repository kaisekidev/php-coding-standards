# Kaiseki PHP Coding Standard

Shared coding-standard tooling for all `kaiseki/*` packages: a PHP-CS-Fixer config and a shared
PHPStan config.

The composer package is `kaiseki/php-coding-standard`; the GitHub repository is
`kaisekidev/php-coding-standards`. (CI lives separately — see below.)

## Installation

```bash
composer require --dev kaiseki/php-coding-standard
```

## PHP-CS-Fixer

Add the following `.php-cs-fixer.dist.php` file to your project's root. This creates a basic
configuration with a Finder that includes everything in `.` and excludes `vendor`:

```php
<?php

declare(strict_types=1);

use Kaiseki\CodingStandard\PhpCsFixer\Config;

return Config::get();
```

If you need a more granular directory specification, you can pass a custom Finder. You can also
override rules by passing your own as the second argument:

```php
<?php

declare(strict_types=1);

use Kaiseki\CodingStandard\PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src');

$rules = [
    // Your custom rules here
];

return Config::get($finder, $rules);
```

## PHPStan

Include the shared PHPStan config from your package's root `phpstan.neon` and add only your own
`paths`:

```neon
includes:
    - vendor/kaiseki/php-coding-standard/phpstan/kaiseki.neon
parameters:
    paths:
        - src
        - tests
```

The shared config runs at `level: max`. The rule extensions (phpstan-strict-rules,
phpstan-phpunit, phpstan-wordpress, phpstan-safe-rule, phpstan-psr-container) are auto-registered
via `phpstan/extension-installer`.

> **Note:** `paths` must live in each package's root `phpstan.neon`, not in the shared config.
> Relative paths in a neon file resolve against the file's own directory (i.e. `vendor/...`), not
> the consuming package root.

## Composer scripts

Add these scripts to your `composer.json`. The reusable CI workflow (below) invokes them by name,
so every package is expected to define them:

```json
{
    "scripts": {
        "cs-check": "php-cs-fixer fix --dry-run",
        "cs-fix": "php-cs-fixer fix --allow-risky=yes",
        "phpstan": "phpstan analyse",
        "phpunit": "phpunit",
        "check-deps": "composer-require-checker"
    }
}
```

## Continuous integration

CI logic is maintained in one place — a reusable workflow in the **`kaisekidev/.github`** repo
(`.github/workflows/checks.yml`). Each package only needs a thin caller. Copy this to
`.github/workflows/checks.yml` in your package:

```yaml
name: Checks

on:
  pull_request:
  push:
    branches: [master]

jobs:
  checks:
    uses: kaisekidev/.github/.github/workflows/checks.yml@v1
```

The workflow runs `check-deps`, `cs-check`, `phpstan`, and `phpunit` across the supported PHP
matrix (default `["8.2","8.3","8.4"]`) with a strict 100% coverage gate by default. Packages not
yet at full coverage override per-package via `coverage-threshold: 0` or `run-tests: false`. The
`@v1` ref is a moving major tag maintained on `kaisekidev/.github`.
