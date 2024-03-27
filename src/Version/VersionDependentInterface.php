<?php

namespace Freezemage\PdfGenerator\Version;

use Freezemage\PdfGenerator\Object\ObjectInterface;

interface VersionDependentInterface extends ObjectInterface
{
    /**
     * @return array<ConstraintInterface>
     */
    public function getConstraints(): array;
}