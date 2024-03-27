<?php

namespace Freezemage\PdfGenerator\Version;

use Freezemage\PdfGenerator\Structure\Header\Version;

interface ConstraintInterface
{
    public function getName(): string;

    public function getPrintableConstraint(): string;

    public function evaluate(Version $currentVersion, Comparator $comparator): bool;
}
