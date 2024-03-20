<?php

namespace Freezemage\PdfGenerator\Object\Graphical;

use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;

final class Rectangle implements ObjectInterface
{
    public function __construct(
        public Coordinate $lowerLeft,
        public Coordinate $upperRight
    ) {
    }

    public function getValue(): ArrayObject
    {
        $dimensions = new ArrayObject();
        if (isset($this->lowerLeft)) {
            $dimensions->push(
                new NumericObject($this->lowerLeft->x),
                new NumericObject($this->lowerLeft->y)
            );
        }
        if (isset($this->upperRight)) {
            $dimensions->push(
                new NumericObject($this->upperRight->x),
                new NumericObject($this->upperRight->y)
            );
        }
        return $dimensions;
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
