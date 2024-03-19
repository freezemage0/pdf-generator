<?php

namespace Freezemage\PdfGenerator\Structure;

use Freezemage\PdfGenerator\Object\IndirectObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Structure\Body\DocumentCatalog;
use Freezemage\PdfGenerator\Structure\Body\PageTree;

final class Body
{
    public readonly DocumentCatalog $documentCatalog;
    private PageTree $rootPage;
    /** @var array<ObjectInterface> */
    private array $objects = [];
    private CrossReferenceTable $crossReferenceTable;

    public function __construct(CrossReferenceTable $crossReferenceTable)
    {
        $this->documentCatalog = new DocumentCatalog();
        $this->crossReferenceTable = $crossReferenceTable;
    }

    public function createPage(): PageTree
    {
        if (!$this->documentCatalog->hasRootPage()) {
            $this->rootPage = new PageTree();
            $this->documentCatalog->setRootPage(new IndirectReference($this->rootPage));
        }

        $page = new PageTree();
        $this->rootPage->addChild($page);

        return $page;
    }

    public function addObject(ObjectInterface $object): void
    {
        $this->objects[] = $object;
    }

    public function compile(): string
    {
        $objects = [
            new IndirectObject($this->documentCatalog),
            new IndirectObject($this->rootPage),

            ...$this->objects
        ];

        foreach ($this->rootPage->collectChildren() as $child) {
            $objects[] = new IndirectObject($child);
        }

        $compiled = [];
        foreach ($objects as $object) {
            if ($object instanceof IndirectObject) {
                $this->crossReferenceTable->register($object);
            }

            $compiled[] = $object->compile();
        }

        return implode("\n", $compiled);
    }
}
