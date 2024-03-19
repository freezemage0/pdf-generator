<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ObjectInterface;

final class LiteralStringObject implements ObjectInterface
{
    public function __construct(public readonly string $value)
    {
    }

    public function compile(): string
    {
        $value = str_replace(['(', ')'], ['\\(', '\\)'], $this->value);

        return "({$value})";
    }
    public function getValue(): string
    {
        return $this->value;
    }
}
