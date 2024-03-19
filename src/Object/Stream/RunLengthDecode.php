<?php

namespace Freezemage\PdfGenerator\Object\Stream;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\NullObject;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\BooleanObject;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;

final class RunLengthDecode implements FilterInterface
{
    private ?DictionaryObject $decodeParams = null;

    private function decodeParams(): DictionaryObject
    {
        return $this->decodeParams ??= new DictionaryObject();
    }

    private function setParam(string $name, ObjectInterface $value): void
    {
        $this->decodeParams()->set(new NameObject($name), $value);
    }

    private function removeParam(string $name): void
    {
        if ($this->decodeParams === null) {
            return;
        }

        $this->decodeParams()->remove(new NameObject($name));
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setEncodingScheme(IndirectReference|NumericObject $k): void
    {
        if ($k instanceof IndirectReference && !$k->isOfType(NumericObject::class)) {
            throw InvalidObjectTypeException::create('K', 'numeric');
        }

        $this->setParam('K', $k);
    }

    public function removeEncodingScheme(): void
    {
        $this->removeParam('K');
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setEndOfLine(IndirectReference|BooleanObject $endOfLine): void
    {
        if ($endOfLine instanceof IndirectReference && !$endOfLine->isOfType(BooleanObject::class)) {
            throw InvalidObjectTypeException::create('EndOfLine', 'boolean');
        }

        $this->setParam('EndOfLine', $endOfLine);
    }

    public function removeEndOfLine(): void
    {
        $this->removeParam('EndOfLine');
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setColumns(IndirectReference|NumericObject $columns): void
    {
        if ($columns instanceof IndirectReference && !$columns->isOfType(NumericObject::class)) {
            throw InvalidObjectTypeException::create('Columns', 'numeric');
        }

        $this->setParam('Columns', $columns);
    }

    public function removeColumns(): void
    {
        $this->removeParam('Columns');
    }

    public function getName(): NameObject
    {
        return new NameObject('RunLengthDecode');
    }

    public function getDecodeParams(): NullObject|DictionaryObject
    {
        return $this->decodeParams ?? new NullObject();
    }
}
