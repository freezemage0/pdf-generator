<?php

namespace Freezemage\PdfGenerator\Object\Stream;

use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\NullObject;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;

interface FilterInterface
{
    public function getName(): NameObject;

    /**
     * Returns a dictionary of additional filter parameters or {@link NullObject} if no parameters are set.
     *
     * @return NullObject|DictionaryObject
     */
    public function getDecodeParams(): NullObject|DictionaryObject;
}
