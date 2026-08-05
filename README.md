# evm-cs-fixer-config

Shared PHP-CS-Fixer ruleset for eyevip PHP repositories. One source of truth for style rules (tabs, brace placement, spacing, import ordering, docblock cosmetics, …), consumed by multiple repos as a versioned dev-dependency.

## Usage

Add to the consuming repo's `composer.json`:

```json
{
	"repositories": [
		{ "type": "vcs", "url": "https://github.com/eyevip/evm-cs-fixer-config" }
	],
	"require-dev": {
		"eyevip/evm-cs-fixer-config": "^1.0"
	}
}
```

Then in that repo's `.php-cs-fixer.php`:

```php
<?php

declare( strict_types = 1 );

use Eyevip\CsFixerConfig\ConfigFactory;
use PhpCsFixer\Finder;

$finder = Finder::create()
	->in( [
		__DIR__ . '/src',
	] )
	->name( '*.php' );

return ConfigFactory::create( $finder );
```

`ConfigFactory::create()` takes the consumer's own `Finder` (paths are inherently per-repo) and returns a ready `PhpCsFixer\Config`. An optional second argument lets a repo override or add individual rules if it genuinely needs to diverge:

```php
return ConfigFactory::create( $finder, [
	'some_rule' => false,
] );
```

Use sparingly — the point of this package is one shared ruleset.

## Updating rules

Rule changes are made here, tagged as a new version, and picked up by consumers explicitly via `composer update eyevip/evm-cs-fixer-config`. This is intentional: consumers adopt new rules on their own schedule instead of being silently reformatted by an automatic bump.

## Enforcement is per-consumer

This package only supplies the ruleset. Whether a consuming repo enforces it (e.g. via a pre-commit hook) is entirely up to that repo — some repos (e.g. `evm-core`) enforce it on every commit, others (e.g. `evm-base`, which carries a lot of unformatted legacy code) run it opportunistically, file by file, with no enforcement at all. Don't assume these postures should match across repos.
