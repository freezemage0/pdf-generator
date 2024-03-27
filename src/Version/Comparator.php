<?php

namespace Freezemage\PdfGenerator\Version;

use Freezemage\PdfGenerator\Structure\Header\Version;

final class Comparator
{
    public function isGreater(Version $version, Version $other): bool
    {
        return version_compare(
            $version->getSemanticVersion(),
            $other->getSemanticVersion(),
            '>'
        );
    }

    public function isLower(Version $version, Version $other): bool
    {
        return version_compare(
            $version->getSemanticVersion(),
            $other->getSemanticVersion(),
            '<'
        );
    }

    public function areEqual(Version $version, Version $other): bool
    {
        return version_compare(
            $version->getSemanticVersion(),
            $other->getSemanticVersion(),
            '='
        );
    }
}