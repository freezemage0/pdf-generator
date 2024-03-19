<?php

namespace Freezemage\PdfGenerator\Object;

final class NullObject implements ObjectInterface
{
    public function compile(): string
    {
        return '';
    }
    public function getValue(): mixed
    {
        return null;
    }
}
