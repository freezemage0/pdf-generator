<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page\Resources;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\OperatesWithIndirectReferences;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;

final class TypeOne implements ReferableObjectInterface
{
    use ReferableObjectImplementation;
    use OperatesWithIndirectReferences;

    private NameObject|IndirectReference $name;
    private NameObject|IndirectReference $baseFont;

    /**
     * @throws InvalidObjectTypeException
     */
    public function setName(IndirectReference|NameObject $name): TypeOne
    {
        $this->validateType($name, NameObject::class);

        $this->name = $name;

        return $this;
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setBaseFont(IndirectReference|NameObject $baseFont): TypeOne
    {
        $this->validateType($baseFont, NameObject::class);

        $this->baseFont = $baseFont;

        return $this;
    }

    public function getValue(): DictionaryObject
    {
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Type'), new NameObject('Font'));
        $dictionary->set(new NameObject('Subtype'), new NameObject('Type1'));
        $dictionary->set(new NameObject('Name'), $this->name);
        $dictionary->set(new NameObject('BaseFont'), $this->baseFont);


        return $dictionary;
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
