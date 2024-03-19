<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ObjectInterface;

final class NameObject implements ObjectInterface
{
    public readonly string $name;

    public function __construct(string $name)
    {
        $this->name = str_replace(['#', ' '], ['#23', '#20'], trim($name));
    }

    public function compile(): string
    {
        return "/{$this->name}";
    }
    public function getValue(): string
    {
        return $this->name;
    }
}
