<?php

namespace Freezemage\PdfGenerator\Version;

use Freezemage\PdfGenerator\Structure\Header\Version;

final class AvailableSince implements ConstraintInterface
{
    public function __construct(public readonly Version $availableSince)
    {
    }

    public function evaluate(): bool
    {
    }
}
