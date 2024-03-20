<?php

namespace Freezemage\PdfGenerator\Object\Stream;

use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\NullObject;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;

final class CcittFaxDecode implements FilterInterface
{
    public function getName(): NameObject
    {
        return new NameObject('CCITTDecode');
    }

    public function getDecodeParams(): NullObject|DictionaryObject
    {
        return new NullObject();
    }
}
