<?php

namespace Freezemage\PdfGenerator\Object;

interface ObjectInterface
{
    public function getValue(): mixed;

    public function compile(): string;
}
