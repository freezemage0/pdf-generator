<?php

namespace Freezemage\PdfGenerator\Object;

use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\Functional\Type;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;

final class FunctionObject implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    private array $range = [];

    public function __construct(public Type $functionType)
    {
    }

    /**
     * @throws MissingRequiredArgumentException
     */
    public function getValue(): DictionaryObject
    {
        $dictionary = new DictionaryObject();
        $dictionary->set(
            new NameObject('FunctionType'),
            new NumericObject($this->functionType->value)
        );

        if ($this->functionType->requiresRange() && empty($this->range)) {
            throw MissingRequiredArgumentException::create('Range');
        }

        $dictionary->set(new NameObject('Range'), new ArrayObject(...$this->range));

        return $dictionary;
    }

    /**
     * @throws MissingRequiredArgumentException
     */
    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}