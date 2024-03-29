<?php

namespace Freezemage\PdfGenerator\Object;

final class IndirectReference implements ObjectInterface
{
    public function __construct(public readonly ObjectInterface $object)
    {
    }

    public function isOfType(string $type): bool
    {
        return $this->object instanceof $type;
    }

    public function getOrigin(): ObjectInterface
    {
        return $this->object;
    }

    public function getValue(): mixed
    {
        return $this->object->getValue();
    }

    public function compile(): string
    {
        $identity = spl_object_id($this->object);
        return "{$identity} 0 R";
    }
}
