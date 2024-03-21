<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use IntlBreakIterator;
use IntlChar;
use Transliterator;
use UConverter;

final class UnicodeStringObject implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    public function __construct(public string $content)
    {
    }

    public function compile(): string
    {
        return '(U+FEFF' . Transliterator::create('Any-Hex/Unicode')->transliterate($this->content) . ')';
    }

    public function getValue(): string
    {
        return $this->content;
    }
}
