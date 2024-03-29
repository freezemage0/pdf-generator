<?php

namespace Freezemage\PdfGenerator\Version\Constraint;

use Freezemage\PdfGenerator\Structure\Header\Version;
use Freezemage\PdfGenerator\Version\Comparator;
use Freezemage\PdfGenerator\Version\ConstraintInterface;

final class ObsoleteSince implements ConstraintInterface
{
    public function __construct(
        private readonly Version $obsoleteSince,
        private readonly string $name
    ) {
    }

    public function evaluate(Version $currentVersion, Comparator $comparator): bool
    {
        return $comparator->isGreater($currentVersion, $this->obsoleteSince);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrintableConstraint(): string
    {
        return "obsolete since {$this->obsoleteSince->value}";
    }
}