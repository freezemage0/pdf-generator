<?php

namespace Freezemage\PdfGenerator\Object\Scalar;

use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use Transliterator;
use UConverter;

final class UnicodeStringObject implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    public function __construct(public string $content)
    {
    }

    public function compile(): string
    {

        $str = '<feff' . bin2hex(
                UConverter::transcode($this->content, 'UTF-16BE', mb_detect_encoding($this->content))
            ) . '>';
        var_dump($str);
        return $str;
    }

    public function getValue(): string
    {
        return $this->content;
    }
}
