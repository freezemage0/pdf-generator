<?php

namespace Freezemage\PdfGenerator\Object;

final class IndirectObject implements ObjectInterface
{
    private int $size;
    private int $identity;

    public function __construct(public readonly ObjectInterface $object)
    {
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function compile(): string
    {
        $compiled = <<<COMPILED
        {$this->identity()} 0 obj
        {$this->object->compile()}
        endobj
        COMPILED;

        if (!isset($this->size)) {
            $this->size = strlen($compiled);
        }

        return $compiled;
    }

    public function getValue(): ObjectInterface
    {
        return $this->object;
    }

    private function identity(): int
    {
        return $this->identity ??= spl_object_id($this->object);
    }
}
