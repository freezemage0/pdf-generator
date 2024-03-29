<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;

final class NameObject implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    public function __construct(public readonly string $name)
    {
    }

    public function compile(): string
    {
        return "/$this->name";
    }

    public function getValue(): string
    {
        return $this->name;
    }
}
