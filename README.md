# Kaiseki PHP Coding Standard

## PHPCS

### Basic Usage:
Add the following `phpcs.xml` file to your project's root:
```xml
<?xml version="1.0"?>
<ruleset>
    <rule ref="Kaiseki"/>

    <file>src</file>
    <file>tests</file>
</ruleset>
```

### Excluding sniffs:
#### For certain files:
To exclude a sniff for a certain set of files, reference  the rule explicitly and add an exclude pattern:

```xml
<rule ref="SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint">
    <exclude-pattern>*Interface.php</exclude-pattern>
</rule>
```
#### Suppressing sniffs locally
To suppress a sniff directly in the code, use the `@phpcsSuppress` annotation:
```php
/**
 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
 * @param int $bar
 */
public function foo($bar = 0): int
{
}
```

More info on this can be found [here](https://github.com/slevomat/coding-standard#suppressing-sniffs-locally).
#### Exclude a whole sniff:
It is also possible to exclude a sniff completely:
```xml
<?xml version="1.0"?>
<ruleset>
    <rule ref="Kaiseki">
        <exclude name="SlevomatCodingStandard.Classes.ClassConstantVisibility"/>
    </rule>

    <file>src</file>
    <file>tests</file>
</ruleset>
```

## PHP-CS-Fixer
Add the following `.php-cs-fixer.php` file to your project's root:

```php
<?php

declare(strict_types=1);

use Kaiseki\CodingStandard\PhpCsFixerConfig;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src');

return PhpCsFixerConfig::get($finder, []);
```

Add this script to your `composer.json`: 
```json
{
    "scripts": {
        "php-cs-fixer": "vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php"
    }
}
```
