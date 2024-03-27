<?php

namespace Freezemage\PdfGenerator;

use Freezemage\PdfGenerator\Exception\InvalidArgumentValueException;
use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;
use Freezemage\PdfGenerator\Structure\Body;
use Freezemage\PdfGenerator\Structure\CrossReferenceTable;
use Freezemage\PdfGenerator\Structure\Header;
use Freezemage\PdfGenerator\Structure\Header\Version;
use Freezemage\PdfGenerator\Structure\Trailer;
use Freezemage\PdfGenerator\Version\Comparator;
use Freezemage\PdfGenerator\Version\ConstraintException;
use Freezemage\PdfGenerator\Version\Evaluator;

final class Document
{
    private readonly Header $header;
    private readonly Body $body;
    private readonly CrossReferenceTable $crossReferenceTable;
    private readonly Evaluator $versionEvaluator;

    public function __construct(Version $version, ?Evaluator $evaluator = null)
    {
        $this->header = new Header($version);
        $this->crossReferenceTable = new CrossReferenceTable();
        $this->body = new Body($this->crossReferenceTable);
        $this->versionEvaluator = $evaluator ?? new Evaluator(new Comparator(), $version);
    }

    public function createPage(): Body\PageTree
    {
        return $this->body->createPageTree();
    }

    public function appendToBody(ReferableObjectInterface ...$objects): void
    {
        foreach ($objects as $object) {
            $this->body->addObject($object->toIndirectObject());
        }
    }

    /**
     * @throws InvalidArgumentValueException
     * @throws MissingRequiredArgumentException
     * @throws ConstraintException
     */
    public function save(string $filepath): void
    {
        $directory = dirname($filepath);
        if (!is_dir($directory)) {
            throw new InvalidArgumentValueException('File directory does not exist.');
        }

        $descriptor = fopen($filepath, 'wb');

        $header = $this->header->compile() . "\n";
        fwrite($descriptor, $header);

        $compiledBody = $this->body->compile($this->versionEvaluator) . "\n";
        fwrite($descriptor, $compiledBody);

        $compiledXrefTable = $this->crossReferenceTable->compile(strlen($header)) . "\n\n";
        fwrite($descriptor, $compiledXrefTable);

        $trailer = new Trailer();
        $trailer->setRoot($this->body->documentCatalog->toIndirectReference());
        $trailer->setSize(new NumericObject($this->crossReferenceTable->count()));
        $trailer->setLastXrefSectionOffset(strlen($compiledBody));

        fwrite($descriptor, $trailer->compile());

        fclose($descriptor);
    }
}
