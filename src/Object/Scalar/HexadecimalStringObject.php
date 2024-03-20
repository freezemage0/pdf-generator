<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;

final class HexadecimalStringObject implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    public function __construct(public readonly string $hex)
    {
    }

    public function compile(): string
    {
        return "<{$this->hex}>";
    }

    public function getValue(): string
    {
        return $this->hex;
    }
}
