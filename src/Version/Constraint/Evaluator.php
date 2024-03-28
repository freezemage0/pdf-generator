<?php

namespace Freezemage\PdfGenerator\Version\Constraint;

use Freezemage\PdfGenerator\Structure\Header\Version;
use Freezemage\PdfGenerator\Version\Comparator;
use Freezemage\PdfGenerator\Version\ConstraintException;
use Freezemage\PdfGenerator\Version\VersionDependentInterface;

final class Evaluator
{
    public function __construct(
        private readonly Comparator $comparator,
        private readonly Version $currentVersion
    ) {
    }

    /**
     * @throws ConstraintException
     */
    public function evaluate(VersionDependentInterface $object): void
    {
        foreach ($object->getConstraints() as $constraint) {
            if (!$constraint->evaluate($this->currentVersion, $this->comparator)) {
                throw ConstraintException::create($constraint, $this->currentVersion);
            }
        }
    }
}
