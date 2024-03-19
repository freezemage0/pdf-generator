<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ObjectInterface;

final class HexadecimalStringObject implements ObjectInterface
{
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
