<?php

namespace Freezemage\PdfGenerator\Version;

use Exception;
use Freezemage\PdfGenerator\ExceptionInterface;
use Freezemage\PdfGenerator\Structure\Header\Version;

final class ConstraintException extends Exception implements ExceptionInterface
{
    public static function create(ConstraintInterface $constraint, Version $currentVersion): ConstraintException
    {
        return new ConstraintException(
            sprintf(
                "Feature '%s' is unavailable for current version (%s). Feature is %s",
                $constraint->getName(),
                $currentVersion->value,
                $constraint->getPrintableConstraint()
            )
        );
    }
}