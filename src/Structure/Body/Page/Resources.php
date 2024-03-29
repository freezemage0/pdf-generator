<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Structure\Body\Page\Resources\Font;
use Freezemage\PdfGenerator\Structure\Body\Page\Resources\Pattern;
use Freezemage\PdfGenerator\Structure\Body\Page\Resources\Procedure;

final class Resources implements ObjectInterface
{
    private DictionaryObject $font;
    private DictionaryObject $pattern;
    private ArrayObject $procedureSet;

    public function __construct()
    {
        $this->font = new DictionaryObject();
        $this->pattern = new DictionaryObject();
        $this->procedureSet = new ArrayObject();
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function addPattern(IndirectReference|Pattern $pattern): void
    {
        if ($pattern instanceof IndirectReference) {
            $origin = $pattern->getOrigin();
            if (!($origin instanceof Pattern)) {
                throw InvalidObjectTypeException::create('Pattern', 'pattern');
            }

            $name = $origin->getName();
        } else {
            $name = $pattern->getName();
        }

        $this->pattern->set($name, $pattern);
    }

    /**
     * @param IndirectReference|Font $font
     *
     * @throws InvalidObjectTypeException
     */
    public function addFont(IndirectReference|Font $font): void
    {
        if ($font instanceof IndirectReference) {
            if (!$font->isOfType(Font::class)) {
                throw InvalidObjectTypeException::create('Font', 'font');
            }

            $origin = $font->object->getValue();
            $name = $origin->getName();
        } else {
            $name = $font->getName();
        }

        $this->font->set($name, $font);
    }

    public function addProcedure(Procedure ...$procedures): void
    {
        foreach ($procedures as $procedure) {
            $this->procedureSet->push(new NameObject($procedure->value));
        }
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

        if (!$this->pattern->isEmpty()) {
            $dictionary->set(new NameObject('Pattern'), $this->pattern);
        }

        return $dictionary;
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
