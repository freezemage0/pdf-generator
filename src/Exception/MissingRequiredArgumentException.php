<?php

namespace Freezemage\PdfGenerator\Exception;

use Exception;
use Freezemage\PdfGenerator\ExceptionInterface;

final class MissingRequiredArgumentException extends Exception implements ExceptionInterface
{
    public static function create(string $argument): MissingRequiredArgumentException
    {
        return new MissingRequiredArgumentException("Missing required argument '{$argument}'");
    }
}
