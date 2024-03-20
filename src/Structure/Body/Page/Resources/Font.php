<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page\Resources;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;

final class Font implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    private NameObject|IndirectReference $name;
    private NameObject|IndirectReference $subType;
    private NameObject|IndirectReference $baseFont;

    public function __construct(
        NameObject|IndirectReference $name = null,
        NameObject|IndirectReference $subType = null,
        NameObject|IndirectReference $baseFont = null
    ) {
        if (isset($name)) {
            $this->setName($name);
        }

        if (isset($subType)) {
            $this->setSubtype($subType);
        }

        if (isset($baseFont)) {
            $this->setBaseFont($baseFont);
        }
    }


    /**
     * @throws InvalidObjectTypeException
     */
    public function setName(IndirectReference|NameObject $name): void
    {
        if ($name instanceof IndirectReference && !$name->isOfType(NameObject::class)) {
            throw InvalidObjectTypeException::create('Name', 'name');
        }

        $this->name = $name;
    }

    public function getName(): NameObject|IndirectReference
    {
        return $this->name;
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setSubtype(IndirectReference|NameObject $subtype): void
    {
        if ($subtype instanceof IndirectReference && !$subtype->isOfType(NameObject::class)) {
            throw InvalidObjectTypeException::create('SubType', 'name');
        }

        $this->subType = $subtype;
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setBaseFont(IndirectReference|NameObject $baseFont): void
    {
        if ($baseFont instanceof IndirectReference && !$baseFont->isOfType(NameObject::class)) {
            throw InvalidObjectTypeException::create('BaseFont', 'name');
        }

        $this->baseFont = $baseFont;
    }

    public function getValue(): DictionaryObject
    {
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Type'), new NameObject('Font'));
        if (isset($this->subType)) {
            $dictionary->set(new NameObject('Subtype'), $this->subType);
        }
        if (isset($this->baseFont)) {
            $dictionary->set(new NameObject('BaseFont'), $this->baseFont);
        }
        if (isset($this->name)) {
            $dictionary->set(new NameObject('Name'), $this->name);
        }

        return $dictionary;
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
