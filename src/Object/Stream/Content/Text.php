<?php

namespace Freezemage\PdfGenerator\Object\Stream\Content;

use Freezemage\PdfGenerator\Object\Graphical\Coordinate;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Stream\ContentInterface;

final class Text implements ContentInterface
{
    private NameObject $fontName;
    private int $fontPoint;
    private Coordinate $startingPosition;

    public function __construct(public ObjectInterface $content)
    {
    }

    public function setFontName(NameObject $fontName): void
    {
        $this->fontName = $fontName;
    }

    public function setFontPoint(int $point): void
    {
        $this->fontPoint = $point;
    }

    public function setStartingPosition(Coordinate $position): void
    {
        $this->startingPosition = $position;
    }

    public function render(): string
    {
        return <<<RENDERED
        BT
            {$this->fontName->compile()} {$this->fontPoint} Tf
            {$this->startingPosition->x} {$this->startingPosition->y} Td
            {$this->content->compile()} Tj
        ET
        RENDERED;
    }

    public function length(): int
    {
        return strlen($this->content->getValue());
    }
}
