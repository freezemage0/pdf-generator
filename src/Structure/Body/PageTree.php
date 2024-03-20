<?php

namespace Freezemage\PdfGenerator\Structure\Body;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;

final class PageTree implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    private ?PageTree $parent = null;

    /** @var array<PageTree|PageObject> */
    private array $children = [];

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
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Type'), new NameObject('Pages'));
        if (isset($this->parent)) {
            $dictionary->set(new NameObject('Parent'), $this->parent->toIndirectReference());
        }

        $childrenReferences = new ArrayObject();
        foreach ($this->children as $child) {
            $childrenReferences->push($child->toIndirectReference());
        }

        $dictionary->set(new NameObject('Kids'), $childrenReferences);
        $dictionary->set(new NameObject('Count'), new NumericObject($this->getCount()));

        return $dictionary;
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}
