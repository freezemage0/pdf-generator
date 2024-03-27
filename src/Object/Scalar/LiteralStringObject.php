<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;

final class LiteralStringObject implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

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
