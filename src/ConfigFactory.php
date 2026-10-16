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
			'compact_nullable_type_declaration' => true,
			// PHP 8.4 deprecates implicit nullable:  string $x = null  →  ?string $x = null
			'nullable_type_declaration_for_default_null_value' => true,

			// other operators:  !$a, ...$args, (string) $x, 'a' . $b, $a ? $b : $c, $obj->prop
			'unary_operator_spaces' => true,
			'cast_spaces' => true,
			'concat_space' => ['spacing' => 'one'],
			'ternary_operator_spaces' => true,
			'object_operator_without_whitespace' => true,
			'no_spaces_around_offset' => true,
			'ternary_to_null_coalescing' => true,

			// control structures:  elseif, for( $i = 0; $i < $n; $i++ ), case 'x':
			'elseif' => true,
			'space_after_semicolon' => true,
			'no_singleline_whitespace_before_semicolons' => true,
			'switch_case_space' => true,
			'switch_case_semicolon_to_colon' => true,
			'no_multiple_statements_per_line' => true,
			'no_empty_statement' => true,
			'include' => true,

			// casing:  true/false/null, keywords, casts, magic constants, native functions and classes
			'constant_case' => true,
			'lowercase_keywords' => true,
			'lowercase_cast' => true,
			'short_scalar_cast' => true,
			'magic_constant_casing' => true,
			'native_function_casing' => true,
			'integer_literal_case' => true,
			'class_reference_name_casing' => true,

			// strings:  "Guest #{$guest->id}"
			'explicit_string_variable' => true,

			// list() → [ $a, $b ] = …
			'list_syntax' => true,

			// class members:  explicit visibility, one property/constant per statement
			'modifier_keywords' => true,
			'single_class_element_per_statement' => true,

			// file structure:  open tag, blank line, code; no closing tag in pure-PHP files
			'full_opening_tag' => true,
			'blank_line_after_opening_tag' => true,
			'no_closing_tag' => true,
			'blank_line_after_namespace' => true,
			'single_line_after_imports' => true,

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
