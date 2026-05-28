# Kaiseki PHP Coding Standard

Shared coding-standard tooling for all `kaiseki/*` packages: a PHP-CS-Fixer config, a shared
PHPStan config, a reusable GitHub Actions workflow, and copy-in templates for new packages.

The composer package is `kaiseki/php-coding-standard`; the GitHub repository (used for the
reusable workflow ref) is `kaisekidev/php-coding-standards`.

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

CI logic is maintained in one place — the reusable workflow at
`.github/workflows/php-quality.yml`. Each package only needs a thin caller that delegates to it.

Copy `templates/ci.yml` to `.github/workflows/ci.yml` in your package:

```yaml
name: CI

on:
  push:
    branches: [master]
  pull_request:

permissions:
  contents: read

jobs:
  quality:
    uses: kaisekidev/php-coding-standards/.github/workflows/php-quality.yml@v1
```

The workflow runs the matrix of supported PHP versions (default `["8.2","8.3","8.4"]`) and invokes
`composer validate`, `check-deps`, `cs-check`, `phpstan`, and `phpunit`. The `@v1` ref is a moving
major tag; pin to an exact tag (e.g. `@1.0.0`) for fully reproducible runs.

## Templates

The `templates/` directory holds copy-in starting points for new `kaiseki/*` packages:

| Template                  | Copy to                        | Purpose                                                    |
| ------------------------- | ------------------------------ | ---------------------------------------------------------- |
| `templates/ci.yml`        | `.github/workflows/ci.yml`     | Thin caller for the reusable CI workflow.                  |
| `templates/dependabot.yml`| `.github/dependabot.yml`       | Weekly dev-tooling and GitHub Actions updates.             |
| `templates/phpunit.xml`   | `phpunit.xml`                  | PHPUnit 11 base config; adjust testsuite names/paths.      |
