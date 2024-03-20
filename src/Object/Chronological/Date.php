<?php

namespace Freezemage\PdfGenerator\Object\Chronological;

use DateTime;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;

final class Date implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    public function __construct(public DateTime $date)
    {
    }

    public function getValue(): DateTime
    {
        return $this->date;
    }

    public function compile(): string
    {
        return 'D:' . strtr($this->getValue()->format('YmdHisP'), ':', "'");
    }
}