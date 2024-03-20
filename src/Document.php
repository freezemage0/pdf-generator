<?php

namespace Freezemage\PdfGenerator;

use Freezemage\PdfGenerator\Exception\InvalidArgumentValueException;
use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\IndirectObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;
use Freezemage\PdfGenerator\Object\Stream;
use Freezemage\PdfGenerator\Structure\Body;
use Freezemage\PdfGenerator\Structure\CrossReferenceTable;
use Freezemage\PdfGenerator\Structure\Header;
use Freezemage\PdfGenerator\Structure\Header\Version;
use Freezemage\PdfGenerator\Structure\Trailer;

final class Document
{
    private readonly Header $header;
    private readonly Body $body;
    private readonly CrossReferenceTable $crossReferenceTable;

    public function __construct(Version $version)
    {
        $this->header = new Header($version);
        $this->crossReferenceTable = new CrossReferenceTable();
        $this->body = new Body($this->crossReferenceTable);
    }

    public function createPage(): Body\PageTree
    {
        return $this->body->createPageTree();
    }

    public function appendToBody(ObjectInterface $object): void
    {
        $this->body->addObject($object);
    }

    public function createStream(?Stream\ContentInterface $content = null): Stream
    {
        $stream = new Stream();
        if ($content !== null) {
            $stream->setContent($content);
        }

        $this->body->addObject($stream->toIndirectObject());

        return $stream;
    }

    /**
     * @throws InvalidArgumentValueException
     * @throws MissingRequiredArgumentException
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

        $compiledBody = $this->body->compile() . "\n";
        fwrite($descriptor, $compiledBody);

        $compiledXrefTable = $this->crossReferenceTable->compile(strlen($header)) . "\n\n";
        fwrite($descriptor, $compiledXrefTable);

        $trailer = new Trailer();
        $trailer->setRoot(new IndirectReference($this->body->documentCatalog));
        $trailer->setSize(new NumericObject($this->crossReferenceTable->count()));
        $trailer->setLastXrefSectionOffset(strlen($compiledBody));

        fwrite($descriptor, $trailer->compile());

        fclose($descriptor);
    }
}
