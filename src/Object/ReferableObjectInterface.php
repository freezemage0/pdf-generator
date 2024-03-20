<?php

namespace Freezemage\PdfGenerator\Object;

/**
 * Objects of this interface may be used indirectly.
 */
interface ReferableObjectInterface extends ObjectInterface
{
    public function toIndirectObject(): IndirectObject;

    public function toIndirectReference(): IndirectReference;
}