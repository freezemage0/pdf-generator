<?php

namespace Freezemage\PdfGenerator\Exception;

use Exception;
use Freezemage\PdfGenerator\ExceptionInterface;

final class InvalidObjectTypeException extends Exception implements ExceptionInterface
{
    public static function create(string $name, string $expectedType): InvalidObjectTypeException
    {
        return new InvalidObjectTypeException("Object {$name} is of invalid type, expected type {$expectedType}");
    }
}
