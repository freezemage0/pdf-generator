<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;

final class BooleanObject implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

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
