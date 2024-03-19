<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ObjectInterface;

final class NumericObject implements ObjectInterface
{
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
