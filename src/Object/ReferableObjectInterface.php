<?php

namespace Freezemage\PdfGenerator\Object;

interface ReferableObjectInterface extends ObjectInterface
{
    public function toIndirectObject(): IndirectObject;

    public function toIndirectReference(): IndirectReference;
}