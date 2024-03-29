<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page\Resources\Font;

enum DescriptorFlag: int
{
    case FIXED_PITCH = 1;
    case SERIF = 2;
    case SYMBOLIC = 3;
    case SCRIPT = 4;
    case NON_SYMBOLIC = 6;
    case ITALIC = 7;
    case ALL_CAP = 17;
    case SMALL_CAP = 18;
    case FORCE_BOLD = 19;

    public function toBinary(): int
    {
        return 1 << ($this->value - 1);
    }
}
