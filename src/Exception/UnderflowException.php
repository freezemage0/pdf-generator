<?php

namespace Freezemage\PdfGenerator\Exception;

use Exception;
use Freezemage\PdfGenerator\ExceptionInterface;

final class UnderflowException extends Exception implements ExceptionInterface
{
    public static function create(): UnderflowException
    {
        return new UnderflowException('Cannot remove value from empty data collection.');
    }
}