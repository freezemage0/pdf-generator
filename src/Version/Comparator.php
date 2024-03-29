<?php

namespace Freezemage\PdfGenerator\Version;

use Freezemage\PdfGenerator\Structure\Header\Version;

final class Comparator
{
    /**
     * @param Version $version
     * @param Version $other
     * @return bool True if $version > $other, false otherwise.
     */
    public function isGreater(Version $version, Version $other, bool $strict = false): bool
    {
        return version_compare(
            $version->getSemanticVersion(),
            $other->getSemanticVersion(),
            $strict ? '>' : '>='
        );
    }

    /**
     * @param Version $version
     * @param Version $other
     * @return bool True if $version < $other, false otherwise.
     */
    public function isLower(Version $version, Version $other, bool $strict = false): bool
    {
        return version_compare(
            $version->getSemanticVersion(),
            $other->getSemanticVersion(),
            $strict ? '<' : '<='
        );
    }
}