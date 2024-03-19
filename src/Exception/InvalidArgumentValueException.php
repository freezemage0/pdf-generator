<?php

namespace Freezemage\PdfGenerator\Exception;

use Exception;
use Freezemage\PdfGenerator\ExceptionInterface;

final class InvalidArgumentValueException extends Exception implements ExceptionInterface
{
    public static function createPositiveInteger(string $argument): InvalidArgumentValueException
    {
        return new InvalidArgumentValueException("Argument {$argument} must be a positive integer.");
    }

    public static function createNotOneOf(string $argument, mixed $value, array $list): InvalidArgumentValueException
    {
        $list = '[' . implode(', ', $list) . ']';

        return new InvalidArgumentValueException("Value ({$value}) of argument {$argument} must be one-of: {$list}");
    }
}
