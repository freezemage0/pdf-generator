<?php

namespace Freezemage\PdfGenerator\Object\Stream;

interface ContentInterface
{
    public function render(): string;
}
