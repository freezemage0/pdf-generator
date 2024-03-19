<?php

namespace Freezemage\PdfGenerator\Structure\Body;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Stream;
use Freezemage\PdfGenerator\Structure\Body\Page\Rectangle;
use Freezemage\PdfGenerator\Structure\Body\Page\Resources;

final class PageObject implements ObjectInterface
{
    private PageTree $parent;
    private Rectangle $mediaBox;
    private IndirectReference|Stream $contents;
    private Resources $resources;

    public function setParent(PageTree $parent): void
    {
        $this->parent = $parent;
    }

    public function setMediaBox(Rectangle $mediaBox): void
    {
        $this->mediaBox = $mediaBox;
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setContents(IndirectReference|Stream $contents): void
    {
        if ($contents instanceof IndirectReference && !$contents->isOfType(Stream::class)) {
            throw InvalidObjectTypeException::create('Contents', 'stream');
        }

        $this->contents = $contents;
    }

    public function setResources(Resources $resources): void
    {
        $this->resources = $resources;
    }

    public function getValue(): mixed
    {
        return null;
    }

    public function compile(): string
    {
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Type'), new NameObject('Page'));
        $dictionary->set(new NameObject('Parent'), new IndirectReference($this->parent));
        $dictionary->set(new NameObject('MediaBox'), $this->mediaBox);

        if (isset($this->resources)) {
            $dictionary->set(new NameObject('Resources'), $this->resources);
        }

        if (isset($this->contents)) {
            $dictionary->set(new NameObject('Contents'), $this->contents);
        }

        return $dictionary->compile();
    }
}
