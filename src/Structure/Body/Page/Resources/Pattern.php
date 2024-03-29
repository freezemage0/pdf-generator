<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page\Resources;

use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;

final class Pattern implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    private ?NameObject $name = null;

    public function getName(): ?NameObject
    {
        return $this->name;
    }

    public function setName(NameObject $name): void
    {
        $this->name = $name;
    }

    public function getValue(): DictionaryObject
    {
        return new DictionaryObject();
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}