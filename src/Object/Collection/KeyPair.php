<?php

namespace Freezemage\PdfGenerator\Object\Collection;

use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;

final class KeyPair
{
    public function __construct(
        public readonly NameObject $key,
        public readonly ObjectInterface $value
    ) {
    }
}
