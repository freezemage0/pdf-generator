<?php

namespace Freezemage\PdfGenerator\Structure;

use Freezemage\PdfGenerator\Encoding\CharacterSet;
use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\IndirectObject;
use Freezemage\PdfGenerator\Structure\Body\DocumentCatalog;
use Freezemage\PdfGenerator\Structure\Body\PageTree;

final class Body
{
    /** @var array<IndirectObject> */
    private array $objects = [];
    private ?PageTree $rootPage = null;
    public readonly DocumentCatalog $documentCatalog;
    private readonly CrossReferenceTable $crossReferenceTable;

    public function __construct(CrossReferenceTable $crossReferenceTable)
    {
        $this->documentCatalog = new DocumentCatalog();
        $this->crossReferenceTable = $crossReferenceTable;
    }

    public function createPageTree(): PageTree
    {
        if ($this->rootPage === null) {
            $this->rootPage = new PageTree();

            try {
                $this->documentCatalog->setRootPage($this->rootPage->toIndirectReference());
            } catch (InvalidObjectTypeException) {
                // Suppressed, never happens.
            }
        }

        return $this->rootPage->createPageTree();
    }

    /**
     * See Adobe PDF Specification p. 7.5.3 "File Body":
     *
     * The body of a PDF file shall consist of a sequence of indirect objects.
     *
     * @param IndirectObject $object
     * @return void
     */
    public function addObject(IndirectObject $object): void
    {
        $this->objects[] = $object;
    }

    public function compile(): string
    {
        $objects = [
            $this->documentCatalog->toIndirectObject(),
            $this->rootPage->toIndirectObject(),
            ...$this->objects
        ];

        foreach ($this->rootPage->collectChildren() as $child) {
            $objects[] = $child->toIndirectObject();
        }

        $compiled = [];
        foreach ($objects as $object) {
            $this->crossReferenceTable->register($object);

            $compiled[] = $object->compile();
        }

        return implode("\n", $compiled);
    }
}
