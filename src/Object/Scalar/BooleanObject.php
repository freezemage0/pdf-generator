<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ObjectInterface;

final class BooleanObject implements ObjectInterface
{
    public function __construct(public readonly bool $value)
    {
    }

    public function compile(): string
    {
        return $this->value ? 'true' : 'false';
    }

    public function getValue(): bool
    {
        return $this->value;
    }
}
