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
