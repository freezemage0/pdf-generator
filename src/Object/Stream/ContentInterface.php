<?php

namespace Freezemage\PdfGenerator\Object\Stream;

interface ContentInterface
{
    public function length(): int;

    public function render(): string;
}
