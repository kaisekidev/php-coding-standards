<?php

declare(strict_types=1);

namespace Kaiseki\CodingStandard;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

class PhpCsFixerConfig {
    public static function get(Finder $finder, array $customRules = []): Config {
        $rules = [];
        $dir = new \DirectoryIterator(dirname(__DIR__, 1) .'/php-cs-fixer-rules');
        foreach ($dir as $item) {
            if ($item->isDot()) {
                continue;
            }
            $rules = array_merge($rules, require $item->getPathname());
        }
        $rules = array_merge($rules, $customRules);

        $config = new Config();
        return $config
            ->setFinder($finder)
            ->setRiskyAllowed(true)
            ->setRules($rules);
    }
}
