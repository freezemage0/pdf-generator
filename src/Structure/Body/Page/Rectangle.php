<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page;

use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\Graphical\Coordinate;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;

final class Rectangle implements ObjectInterface
{
    private ArrayObject $dimensions;

    public function __construct(
        public Coordinate $lowerLeft,
        public Coordinate $upperRight
    ) {
        $this->dimensions = new ArrayObject();
    }


    public function getValue(): ArrayObject
    {
        return $this->dimensions;
    }

    public function compile(): string
    {
        $this->dimensions->push(
            new NumericObject($this->lowerLeft->x),
            new NumericObject($this->lowerLeft->y),
            new NumericObject($this->upperRight->x),
            new NumericObject($this->upperRight->y),
        );

        return $this->dimensions->compile();
    }
}
