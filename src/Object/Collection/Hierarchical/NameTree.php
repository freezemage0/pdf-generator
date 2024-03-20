<?php

namespace Freezemage\PdfGenerator\Object\Collection\Hierarchical;

use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;

final class NameTree implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    public function getValue(): DictionaryObject
    {
        return new DictionaryObject();
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}