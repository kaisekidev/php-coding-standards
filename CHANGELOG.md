# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## Unreleased

### Changed

- CI moved out of this repo: the reusable GitHub Actions workflow now lives in `kaisekidev/.github`
  (`checks.yml@v1`), so each package's caller references that repo. This package keeps the
  PHP-CS-Fixer config, the shared PHPStan config, and the dependabot/phpunit templates.

### Removed

- `.github/workflows/php-quality.yml` and `templates/ci.yml` (relocated to `kaisekidev/.github`).
- The `v1` git tag — it collided with `1.0.0` on Packagist (both normalize to `1.0.0.0`); the
  moving `v1` tag now lives only on `kaisekidev/.github`.

## 1.0.0 - 2026-05-28

First tagged release. Establishes the shared coding-standard toolkit for all `kaiseki/*` packages.

### Added

- PHP-CS-Fixer configuration via `Kaiseki\CodingStandard\PhpCsFixer\Config`, with an optional
  custom Finder and rule overrides.
- Shared PHPStan configuration (`phpstan/kaiseki.neon`) running at `level: max`, relying on
  `phpstan/extension-installer` to auto-register the rule extensions.
- Reusable GitHub Actions workflow (`.github/workflows/php-quality.yml`, ref `@v1`) that runs
  `composer validate`, `check-deps`, `cs-check`, `phpstan`, and `phpunit` across a PHP version
  matrix (default `8.2`, `8.3`, `8.4`).
- Copy-in templates for new packages: `templates/ci.yml`, `templates/dependabot.yml`, and a
  PHPUnit 11 `templates/phpunit.xml`.

### Changed

- Broadened the PHP requirement to `^8.2` (targeting 8.4).
