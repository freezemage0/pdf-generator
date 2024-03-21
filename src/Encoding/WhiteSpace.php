<?php

namespace Freezemage\PdfGenerator\Encoding;

enum WhiteSpace: string
{
    case NUL = '\0';
    case HORIZONTAL_TAB = '\t';
    case LINE_FEED = '\n';
    case FORM_FEED = '\f';
    case CARRIAGE_RETURN = '\r';
    case SPACE = '\ ';
}
