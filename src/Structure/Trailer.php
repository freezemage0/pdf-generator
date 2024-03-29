<?php

namespace Freezemage\PdfGenerator\Structure;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;
use Freezemage\PdfGenerator\Structure\Body\DocumentCatalog;

final class Trailer implements ObjectInterface
{
    private IndirectReference $root;
    private NumericObject $size;
    private int $lastXrefSectionOffset;

    public function __construct()
    {
    }

    public function setSize(NumericObject $size): void
    {
        $this->size = $size;
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setRoot(IndirectReference $root): void
    {
        if (!($root->object instanceof DocumentCatalog)) {
            throw InvalidObjectTypeException::create('Root', 'DocumentCatalog');
        }

        $this->root = $root;
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
        {$this->getValue()->compile()}
        startxref
        {$this->lastXrefSectionOffset}
        %%EOF
        COMPILED;
    }

    /**
     * @throws MissingRequiredArgumentException
     */
    public function getValue(): DictionaryObject
    {
        if (!isset($this->root)) {
            throw MissingRequiredArgumentException::create('Root');
        }
        if (!isset($this->size)) {
            throw MissingRequiredArgumentException::create('Size');
        }

        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Root'), $this->root);
        $dictionary->set(new NameObject('Size'), $this->size);

        return $dictionary;
    }
}
