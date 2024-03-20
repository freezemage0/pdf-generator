<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;

final class NumericObject implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    public function __construct(public readonly int|float $value)
    {
    }

    public function compile(): string
    {
        return (string) $this->value;
    }

    public function getValue(): int|float
    {
        return $this->value;
    }
}
