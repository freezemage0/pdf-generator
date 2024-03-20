<?php

namespace Freezemage\PdfGenerator\Structure;

use Freezemage\PdfGenerator\Object\IndirectObject;

final class CrossReferenceTable
{
    /** @var array<IndirectObject> */
    private array $objects = [];

    public function register(IndirectObject $object): void
    {
        $this->objects[] = $object;
    }

    public function count(): int
    {
        return count($this->objects) + 1; // For invalid offset
    }

    public function compile(int $initialByteOffset): string
    {
        $offsets = ["0000000000 65535 f"]; // Default invalid offset.

        foreach ($this->objects as $object) {
            $offset = str_pad((string) $initialByteOffset, 10, '0', STR_PAD_LEFT);
            $generation = str_pad('0', 5, '0', STR_PAD_LEFT);
            $marker = 'n';

            $offsets[] = "{$offset} {$generation} {$marker} ";
            $initialByteOffset += $object->getSize();
        }

        $offsets = implode("\n", $offsets);

        return <<<COMPILED
        xref
        0 {$this->count()}
        {$offsets}
        COMPILED;
    }
}
