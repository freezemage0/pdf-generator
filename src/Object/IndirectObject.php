<?php

namespace Freezemage\PdfGenerator\Object;

final class IndirectObject implements ObjectInterface
{
    private string $compiledOrigin;
    private int $identity;

    public function __construct(public readonly ObjectInterface $object)
    {
    }

    public function getSize(): int
    {
        return strlen($this->compile());
    }

    public function compile(): string
    {
        return <<<COMPILED
        {$this->identity()} 0 obj
            {$this->compileOrigin()}
        endobj
        
        COMPILED;
    }

    public function getValue(): ObjectInterface
    {
        return $this->object;
    }

    private function compileOrigin(): string
    {
        return $this->compiledOrigin ??= $this->object->compile();
    }

    private function identity(): int
    {
        return $this->identity ??= spl_object_id($this->object);
    }
}
