<?php

namespace Freezemage\PdfGenerator\Object\Functional;

enum Type: int
{
    case SAMPLED = 0;
    case EXPONENTIAL_INTERPOLATION = 2;
    case STITCHING = 3;
    case POST_SCRIPT_CALCULATOR = 4;

    public function requiresRange(): bool
    {
        return $this === Type::SAMPLED || $this === Type::POST_SCRIPT_CALCULATOR;
    }
}