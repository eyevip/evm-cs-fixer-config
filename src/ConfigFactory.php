<?php

declare( strict_types = 1 );

namespace Eyevip\CsFixerConfig;

use Eyevip\CsFixerConfig\CustomFixers\NoSpaceBeforeControlStructureParenthesisFixer;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

final class ConfigFactory {
	public static function create( Finder $finder, array $overrideRules = [] ): Config {
		return ( new Config() )
			->registerCustomFixers( [new NoSpaceBeforeControlStructureParenthesisFixer()] )
			->setIndent( "\t" )
			->setLineEnding( "\n" )
			->setRules( array_merge( self::rules(), $overrideRules ) )
			->setFinder( $finder );
	}

	private static function rules(): array {
		return [
			// indentation & quotes
			'indentation_type' => true,
			'single_quote' => true,

			// indentation depth — indentation_type only picks tabs over spaces
			// statement_indentation also puts `case` one level inside `switch`
			'statement_indentation' => true,
			'array_indentation' => true,
			'method_chaining_indentation' => true,

			// arrays
			'array_syntax' => ['syntax' => 'short'],
			'no_trailing_comma_in_singleline' => true,

			// commas — none before, one after:  foo( $a, $b ), ['a', 'b']
			// on_multiline ignore: fix comma spacing only, leave argument-list wrapping alone
			'method_argument_space' => ['on_multiline' => 'ignore'],
			'whitespace_after_comma_in_array' => true,
			'no_whitespace_before_comma_in_array' => true,

			// declare — strict_types is added manually per file (see CLAUDE.md); only normalise spacing when it already exists
			'declare_equal_normalize' => ['space' => 'single'],

			// braces — same-line everywhere
			'braces_position' => [
				'control_structures_opening_brace' => 'same_line',
				'functions_opening_brace' => 'same_line',
				'anonymous_functions_opening_brace' => 'same_line',
				'classes_opening_brace' => 'same_line',
				'anonymous_classes_opening_brace' => 'same_line',
				'allow_single_line_empty_anonymous_classes' => true,
				'allow_single_line_anonymous_functions' => true,
			],

			// keep empty bodies on one line:  function foo() {}
			'single_line_empty_body' => true,

			// operators — align consecutive = but leave => alone
			'binary_operator_spaces' => [
				'default' => 'single_space',
				'operators' => [
					'=' => 'align_single_space_minimal',
					'=>' => 'single_space',
				],
			],

			// spaces inside ALL parentheses:  if ( $x ), foo( $a, $b ), function f( string $x )
			'spaces_inside_parentheses' => ['space' => 'single'],

			// no space between a function/method call and its "(":  foo( $a ), array_map( ... )
			'no_spaces_after_function_name' => true,

			// no space between a control-structure keyword and its "(":  if( $a ), foreach( $items as $item )
			// custom fixer — PHP-CS-Fixer has no built-in rule for this, see CustomFixers/
			'Eyevip/no_space_before_control_structure_parenthesis' => true,

			// no space before "(" in function/method/closure/arrow-fn declarations:  function foo(), fn( $x ) =>
			'function_declaration' => [
				'closure_function_spacing' => 'none',
				'closure_fn_spacing' => 'none',
			],

			// union/intersection type spacing:  null | int | \DateInterval
			'types_spaces' => ['space' => 'single'],

			// type declarations:  ?callable $fn, function f(): bool
			'nullable_type_declaration' => true,
			'return_type_declaration' => ['space_before' => 'none'],

			// whitespace hygiene
			'no_trailing_whitespace' => true,
			'no_whitespace_in_blank_line' => true,
			'single_blank_line_at_eof' => true,
			'no_extra_blank_lines' => ['tokens' => ['extra']],

			// imports — alphabetical, no unused
			'ordered_imports' => ['sort_algorithm' => 'alpha'],
			'no_unused_imports' => true,

			// class members — one blank line between, none after "{" or before "}"
			'class_attributes_separation' => true,

			// docblocks — cosmetic normalisation only; presence/content stays a judgement call
			'phpdoc_indent' => true,
			'phpdoc_trim' => true,
			'phpdoc_align' => true,
			'phpdoc_scalar' => true,
			'phpdoc_types' => true,
			'no_blank_lines_after_phpdoc' => true,
		];
	}
}
