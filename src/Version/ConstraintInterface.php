<?php

namespace Freezemage\PdfGenerator\Version;

interface ConstraintInterface
{
    public function evaluate(): bool;
}
