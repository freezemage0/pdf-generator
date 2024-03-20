<?php

namespace Freezemage\PdfGenerator\Structure\Header;

enum Version: string
{
    case PDF_1_0 = 'PDF-1.0';
    case PDF_1_1 = 'PDF-1.1';
    case PDF_1_2 = 'PDF-1.2';
    case PDF_1_3 = 'PDF-1.3';
    case PDF_1_4 = 'PDF-1.4';
    case PDF_1_5 = 'PDF-1.5';
    case PDF_1_6 = 'PDF-1.6';
    case PDF_1_7 = 'PDF-1.7';
}
