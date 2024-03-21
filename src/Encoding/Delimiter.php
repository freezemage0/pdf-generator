<?php

namespace Freezemage\PdfGenerator\Encoding;

enum Delimiter: string
{
    case LEFT_PARENTHESIS = '(';
    case RIGHT_PARENTHESIS = ')';
    case LESS_THAN_SIGN = '<';
    case MORE_THAN_SIGN = '>';
    case LEFT_SQUARE_BRACKET = '[';
    case RIGHT_SQUARE_BRACKET = ']';
    case LEFT_CURLY_BRACKET = '{';
    case RIGHT_CURLY_BRACKET = '}';
    case SOLIDUS = '/';
    case PERCENT_SIGN = '%';
}
