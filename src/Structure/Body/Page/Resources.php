<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Structure\Body\Page\Resources\Font;
use Freezemage\PdfGenerator\Structure\Body\Page\Resources\Procedure;

final class Resources implements ObjectInterface
{
    private DictionaryObject $font;
    private ArrayObject $procedureSet;

    public function __construct()
    {
        $this->font = new DictionaryObject();
        $this->procedureSet = new ArrayObject();
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function addFont(NameObject $name, IndirectReference|Font $font): void
    {
        if ($font instanceof IndirectReference && !$font->isOfType(Font::class)) {
            throw InvalidObjectTypeException::create('Font', 'font');
        }

        $this->font->set($name, $font);
    }

    public function addProcedure(Procedure $procedure): void
    {
        $this->procedureSet->push(new NameObject($procedure->value));
    }

    public function getValue(): DictionaryObject
    {
        $dictionary = new DictionaryObject();

        if (!$this->font->isEmpty()) {
            $dictionary->set(new NameObject('Font'), $this->font);
        }

        if (!$this->procedureSet->isEmpty()) {
            $dictionary->set(new NameObject('ProcSet'), $this->procedureSet);
        }

        return $dictionary;
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
