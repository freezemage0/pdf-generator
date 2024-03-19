<?php

namespace Freezemage\PdfGenerator\Structure;

use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;

final class Trailer
{
    private DictionaryObject $dictionary;
    private int $lastXrefSectionOffset;

    public function __construct()
    {
        $this->dictionary = new DictionaryObject();
    }

    public function setSize(NumericObject $size): void
    {
        $this->dictionary->set(new NameObject('Size'), $size);
    }

    public function setRoot(IndirectReference $root): void
    {
        $this->dictionary->set(new NameObject('Root'), $root);
    }

    public function setLastXrefSectionOffset(int $offset): void
    {
        $this->lastXrefSectionOffset = $offset;
    }

    /**
     * @throws MissingRequiredArgumentException
     */
    public function compile(): string
    {
        if (!isset($this->lastXrefSectionOffset)) {
            throw MissingRequiredArgumentException::create('Last cross-reference table section byte offset');
        }

        return <<<COMPILED
        trailer
        {$this->dictionary->compile()}
        startxref
        {$this->lastXrefSectionOffset}
        %%EOF
        COMPILED;
    }
}
