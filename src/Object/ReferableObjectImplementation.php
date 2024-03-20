<?php

namespace Freezemage\PdfGenerator\Object;

trait ReferableObjectImplementation
{
    public function toIndirectObject(): IndirectObject
    {
        return new IndirectObject($this);
    }

    public function toIndirectReference(): IndirectReference
    {
        return new IndirectReference($this);
    }
}