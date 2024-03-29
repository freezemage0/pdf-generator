<?php

namespace Freezemage\PdfGenerator\Object\Collection\Hierarchical;

use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\ObjectInterface;

final class NumberTree implements ObjectInterface
{
    public function getValue(): DictionaryObject
    {
        return new DictionaryObject();
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
