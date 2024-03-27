<?php

namespace Freezemage\PdfGenerator\Version\Constraint;

use Freezemage\PdfGenerator\Structure\Header\Version;
use Freezemage\PdfGenerator\Version\Comparator;
use Freezemage\PdfGenerator\Version\ConstraintInterface;

final class AvailableSince implements ConstraintInterface
{
    public function __construct(
        public readonly string $name,
        public readonly Version $availableSince
    ) {
    }

    public function evaluate(Version $currentVersion, Comparator $comparator): bool
    {
        return !$comparator->isLower($currentVersion, $this->availableSince);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrintableConstraint(): string
    {
        return "since version {$this->availableSince->value}";
    }
}
