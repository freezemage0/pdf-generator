<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page\Resources;

use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;

final class ProcedureSet implements ObjectInterface
{
    /** @var array<Procedure> */
    private array $procedures = [];

    public function addProcedure(Procedure $procedure): void
    {
        $this->procedures[] = $procedure;
    }

    public function getValue(): ArrayObject
    {
        $procedures = new ArrayObject();
        foreach ($this->procedures as $procedure) {
            $procedures->push(new NameObject($procedure->value));
        }

        return $procedures;
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
