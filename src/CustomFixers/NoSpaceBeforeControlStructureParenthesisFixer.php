<?php

declare( strict_types = 1 );

namespace Eyevip\CsFixerConfig\CustomFixers;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;

// PHP-CS-Fixer has no built-in rule for this — no_spaces_after_function_name only covers
// calls/declarations, not control-structure keywords (see NoSpacesAfterFunctionNameFixer::FUNCTIONY_TOKEN_KINDS).
final class NoSpaceBeforeControlStructureParenthesisFixer extends AbstractFixer {
	private const KEYWORDS = [
		\T_IF,
		\T_ELSEIF,
		\T_FOR,
		\T_FOREACH,
		\T_WHILE,
		\T_SWITCH,
		\T_CATCH,
		\T_MATCH,
	];

	public function getName(): string {
		return 'Eyevip/no_space_before_control_structure_parenthesis';
	}

	public function getDefinition(): FixerDefinitionInterface {
		return new FixerDefinition(
			'There must be no space between a control structure keyword (if/elseif/for/foreach/while/switch/catch/match) and its opening parenthesis.',
			[new CodeSample( "<?php\nif ( \$a ) {\n}\nforeach ( \$items as \$item ) {\n}\n" )]
		);
	}

	public function isCandidate( Tokens $tokens ): bool {
		return $tokens->isAnyTokenKindsFound( self::KEYWORDS );
	}

	protected function applyFix( \SplFileInfo $file, Tokens $tokens ): void {
		foreach( $tokens as $index => $token ) {
			if( !$token->isGivenKind( self::KEYWORDS ) ) {
				continue;
			}

			$whitespaceIndex = $index + 1;
			if( !isset( $tokens[$whitespaceIndex] ) || !$tokens[$whitespaceIndex]->isWhitespace() ) {
				continue;
			}

			$parenIndex = $whitespaceIndex + 1;
			if( !isset( $tokens[$parenIndex] ) || !$tokens[$parenIndex]->equals( '(' ) ) {
				continue;
			}

			$tokens->clearAt( $whitespaceIndex );
		}
	}
}
