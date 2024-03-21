<?php

namespace Freezemage\PdfGenerator\Encoding;

use Transliterator;

final class CharacterSet
{
    public function __construct(
        private ?Transliterator $nameTransliterator = null,
        private ?Transliterator $literalStringTransliterator = null
    ) {
    }

    public function createNameTransliterator(): Transliterator
    {
        $nonRegularCharacters = [
            ...WhiteSpace::cases(),
            ...Delimiter::cases(),
            '#'
        ];

        $nonRegularCharacters = implode('', $nonRegularCharacters);

        return $this->nameTransliterator ??= Transliterator::createFromRules(
            <<<NAME_RULES
            :: [[:^ASCII:][{$nonRegularCharacters}]];
            :: Any-Hex/Java;
            :: '\\u00' <> '#';
            NAME_RULES
        );
    }

    public function createLiteralStringTransliterator(): Transliterator
    {
        $nonRegularCharacters = [
            ...WhiteSpace::cases(),
            ...Delimiter::cases()
        ];

        $nonRegularCharacters = implode('', $nonRegularCharacters);

        return $this->literalStringTransliterator ??= Transliterator::createFromRules(
            <<<LITERAL_STRING_RULES
            :: [[:^ASCII:][{$nonRegularCharacters}]];
            :: Any-Hex/Java;
            :: '\\u00' <> '#'
            LITERAL_STRING_RULES
        );
    }
}
