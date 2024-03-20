<?php

namespace Freezemage\PdfGenerator\Structure\Body;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\Graphical\Rectangle;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Structure\Body\Page\Contents;
use Freezemage\PdfGenerator\Structure\Body\Page\Resources;

final class PageObject implements ObjectInterface
{
    private PageTree $parent;
    private Rectangle $mediaBox;
    private Contents $contents;
    private Resources $resources;

    public function setParent(PageTree $parent): void
    {
        $this->parent = $parent;
    }

    public function setMediaBox(Rectangle $mediaBox): void
    {
        $this->mediaBox = $mediaBox;
    }

    public function getContents(): Contents
    {
        return $this->contents ??= new Contents();
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setContents(Contents $contents): void
    {
        $this->contents = $contents;
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setResources(IndirectReference|Resources $resources): void
    {
        if ($resources instanceof IndirectReference && !$resources->isOfType(Resources::class)) {
            throw InvalidObjectTypeException::create('Resources', 'resources');
        }

        $this->resources = $resources;
    }

    public function getResources(): Resources
    {
        return $this->resources ??= new Resources();
    }

    /**
     * @throws MissingRequiredArgumentException
     */
    public function getValue(): DictionaryObject
    {
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Type'), new NameObject('Page'));
        if (!isset($this->parent)) {
            throw new MissingRequiredArgumentException('Parent');
        }

        $dictionary->set(new NameObject('Parent'), new IndirectReference($this->parent));
        $dictionary->set(new NameObject('MediaBox'), $this->mediaBox);

        if (isset($this->resources)) {
            $dictionary->set(new NameObject('Resources'), $this->resources);
        }

        if (isset($this->contents)) {
            $dictionary->set(new NameObject('Contents'), $this->contents);
        }
        return $dictionary;
    }

    /**
     * @throws MissingRequiredArgumentException
     */
    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
