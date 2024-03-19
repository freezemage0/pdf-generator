<?php

namespace Freezemage\PdfGenerator\Object\Stream;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;

final class LzwDecode extends FlateDecode implements FilterInterface
{
    /**
     * @throws InvalidObjectTypeException
     */
    public function setEarlyChange(IndirectReference|NumericObject $earlyChange): void
    {
        if ($earlyChange instanceof IndirectReference) {
            if (!$earlyChange->isOfType(NumericObject::class)) {
                throw InvalidObjectTypeException::create('EarlyChange', 'numeric');
            }
        }

        $this->decodeParams()->set(new NameObject('EarlyChange'), $earlyChange);
    }

    public function removeEarlyChange(): void
    {
        if (!$this->hasDecodeParams()) {
            return;
        }

        $this->decodeParams()->remove(new NameObject('EarlyChange'));
    }

    public function getName(): NameObject
    {
        return new NameObject('LZWDecode');
    }
}
