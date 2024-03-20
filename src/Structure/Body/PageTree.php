<?php

namespace Freezemage\PdfGenerator\Structure\Body;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;

class PageTree implements ObjectInterface
{
    private ?PageTree $parent = null;
    private array $children;

    public function __construct()
    {
        $this->children = [];
    }

    public function addChild(PageTree|PageObject $child): void
    {
        $this->children[] = $child;
        $child->setParent($this);
    }

    public function createPageTree(): PageTree
    {
        $pageTree = new PageTree();
        $this->addChild($pageTree);

        return $pageTree;
    }

    public function createPageObject(): PageObject
    {
        $pageObject = new PageObject();
        $this->addChild($pageObject);

        return $pageObject;
    }

    public function setParent(PageTree $parent): void
    {
        $this->parent = $parent;
    }

    public function getCount(): int
    {
        $count = 0;
        foreach ($this->children as $child) {
            $count += ($child instanceof PageTree) ? $child->getCount() : 1;
        }

        return $count;
    }

    public function collectChildren(): array
    {
        $children = [];
        foreach ($this->children as $child) {
            $children[] = $child;
            if ($child instanceof PageTree) {
                $children = array_merge($children, $child->collectChildren());
            }
        }

        return $children;
    }

    public function getValue(): DictionaryObject
    {
        return new DictionaryObject();
    }

    public function compile(): string
    {
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Type'), new NameObject('Pages'));
        if (isset($this->parent)) {
            $dictionary->set(new NameObject('Parent'), new IndirectReference($this->parent));
        }

        $childrenReferences = new ArrayObject();

        foreach ($this->children as $child) {
            $childrenReferences->push(new IndirectReference($child));
        }

        $dictionary->set(new NameObject('Kids'), $childrenReferences);
        $dictionary->set(new NameObject('Count'), new NumericObject($this->getCount()));

        return $dictionary->compile();
    }
}
