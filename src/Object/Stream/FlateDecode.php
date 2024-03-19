<?php

namespace Freezemage\PdfGenerator\Object\Stream;

use Freezemage\PdfGenerator\Exception\InvalidArgumentValueException;
use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\NullObject;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;

class FlateDecode implements FilterInterface
{
    private ?DictionaryObject $decodeParams = null;

    /**
     * @throws InvalidObjectTypeException
     * @throws InvalidArgumentValueException
     */
    final public function setPredictor(IndirectReference|NumericObject $predictor): void
    {
        if ($predictor instanceof IndirectReference) {
            if (!$predictor->isOfType(NumericObject::class)) {
                throw InvalidObjectTypeException::create('Predictor', 'numeric');
            }
            $value = $predictor->getValue()->getValue();
        } else {
            $value = $predictor->getValue();
        }

        if ($value < 1) {
            throw new InvalidArgumentValueException('Predictor value cannot be lower than 1');
        }

        $this->decodeParams()->set(new NameObject('Predictor'), $predictor);
    }

    final public function removePredictor(): void
    {
        if ($this->decodeParams === null) {
            return;
        }

        $this->decodeParams()->remove(new NameObject('Predictor'));
    }

    final public function getPredictor(): ?ObjectInterface
    {
        if ($this->decodeParams === null) {
            return null;
        }

        return $this->decodeParams()->get('Predictor')->value;
    }

    /**
     * @throws MissingRequiredArgumentException
     */
    private function validatePredictor(): void
    {
        if ($this->getPredictor()?->getValue() < 2) {
            throw new MissingRequiredArgumentException(
                'Predictor value must be set and must be greater than 1'
            );
        }
    }

    /**
     * TODO: Validate color value being in range [1, 4] if the document version is below PDF 1.0
     *
     * @param IndirectReference|NumericObject $colors
     *
     * @return void
     *
     * @throws InvalidObjectTypeException
     * @throws InvalidArgumentValueException
     * @throws MissingRequiredArgumentException
     */
    final public function setColors(IndirectReference|NumericObject $colors): void
    {
        $this->validatePredictor();

        if ($colors instanceof IndirectReference) {
            if (!$colors->isOfType(NumericObject::class)) {
                throw InvalidObjectTypeException::create('Colors', 'numeric');
            }
        }

        $value = $colors->getValue();

        if ($value < 1) {
            throw new InvalidArgumentValueException('Colors value cannot be lower than 1');
        }

        $this->decodeParams()->set(new NameObject('Colors'), $colors);
    }

    final public function removeColors(): void
    {
        if ($this->decodeParams === null) {
            return;
        }

        $this->decodeParams()->remove(new NameObject('Colors'));
    }

    /**
     * @throws InvalidObjectTypeException
     * @throws MissingRequiredArgumentException
     * @throws InvalidArgumentValueException
     */
    final public function setBitsPerComponent(IndirectReference|NumericObject $bitsPerComponent): void
    {
        $this->validatePredictor();

        if ($bitsPerComponent instanceof IndirectReference) {
            if (!$bitsPerComponent->isOfType(NumericObject::class)) {
                throw InvalidObjectTypeException::create('BitsPerComponent', 'numeric');
            }
        }

        $value = $bitsPerComponent->getValue();
        if (!in_array($value, [1, 2, 4, 8], true)) {
            throw InvalidArgumentValueException::createNotOneOf('BitsPerComponent', $value, [1, 2, 4, 8]);
        }

        $this->decodeParams()->set(new NameObject('BitsPerComponent'), $bitsPerComponent);
    }

    final public function removeBitsPerComponent(): void
    {
        if ($this->decodeParams === null) {
            return;
        }

        $this->decodeParams()->remove(new NameObject('BitsPerComponent'));
    }

    /**
     * @throws MissingRequiredArgumentException
     * @throws InvalidArgumentValueException
     * @throws InvalidObjectTypeException
     */
    final public function setColumns(IndirectReference|NumericObject $columns): void
    {
        $this->validatePredictor();

        if ($columns instanceof IndirectReference) {
            if (!$columns->isOfType(NumericObject::class)) {
                throw InvalidObjectTypeException::create('Columns', 'numeric');
            }
        }

        $value = $columns->getValue();
        if ($value < 1) {
            throw InvalidArgumentValueException::createPositiveInteger('Columns');
        }

        $this->decodeParams()->set(new NameObject('Columns'), $columns);
    }

    final public function removeColumns(): void
    {
        if ($this->decodeParams === null) {
            return;
        }

        $this->decodeParams()->remove(new NameObject('Columns'));
    }

    public function getName(): NameObject
    {
        return new NameObject('FlateDecode');
    }

    final public function getDecodeParams(): NullObject|DictionaryObject
    {
        return $this->decodeParams ?? new NullObject();
    }

    final protected function decodeParams(): DictionaryObject
    {
        return $this->decodeParams ??= new DictionaryObject();
    }

    final protected function hasDecodeParams(): bool
    {
        return $this->decodeParams !== null;
    }
}
