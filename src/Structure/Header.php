<?php

namespace Freezemage\PdfGenerator\Structure;

use Freezemage\PdfGenerator\Structure\Header\Version;

final class Header
{
    public function __construct(public Version $version)
    {
    }

    public function compile(): string
    {
        return <<<HEADER
        %{$this->version->value}
        %%EOF
        HEADER;
    }
}
