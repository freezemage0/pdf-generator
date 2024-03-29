<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page\Resources;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\OperatesWithIndirectReferences;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Structure\Body\Page\Resources\Font\Descriptor;

final class Font implements ReferableObjectInterface
{
    use ReferableObjectImplementation;
    use OperatesWithIndirectReferences;

    private NameObject|IndirectReference $name;
    private NameObject|IndirectReference $subType;
    private NameObject|IndirectReference $baseFont;
    private Descriptor|IndirectReference $descriptor;

    /**
     * @throws InvalidObjectTypeException
     */
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
        $this->assertType($name, NameObject::class, 'name');

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
        $this->assertType($subtype, NameObject::class, 'Subtype');

        $this->subType = $subtype;
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setBaseFont(IndirectReference|NameObject $baseFont): void
    {
        $this->assertType($baseFont, NameObject::class, 'BaseFont');

        $this->baseFont = $baseFont;
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setDescriptor(IndirectReference|Descriptor $descriptor): void
    {
        $this->assertType($descriptor, Descriptor::class, 'Descriptor');

        $this->descriptor = $descriptor;
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
        if (isset($this->descriptor)) {
            $dictionary->set(new NameObject('Descriptor'), $this->descriptor);
        }

        return $dictionary;
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
