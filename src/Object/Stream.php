<?php

namespace Freezemage\PdfGenerator\Object;

use Freezemage\PdfGenerator\Exception\InvalidArgumentValueException;
use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;
use Freezemage\PdfGenerator\Object\Stream\ContentInterface;
use Freezemage\PdfGenerator\Object\Stream\FilterInterface;
use Freezemage\PdfGenerator\Structure\Header\Version;
use Freezemage\PdfGenerator\Version\Constraint\AvailableSince;
use Freezemage\PdfGenerator\Version\VersionDependentInterface;

class Stream implements ReferableObjectInterface, VersionDependentInterface
{
    use ReferableObjectImplementation;
    use OperatesWithIndirectReferences;

    private NumericObject|IndirectReference $length;
    private ContentInterface $content;
    private NumericObject|IndirectReference $decodedLength;
    /** @var array<FilterInterface> */
    private array $filters = [];

    /**
     * @throws InvalidObjectTypeException
     * @throws InvalidArgumentValueException
     */
    public function setLength(NumericObject|IndirectReference $length): void
    {
        $this->assertType($length, NumericObject::class);

        if ($length->getValue() < 0) {
            throw InvalidArgumentValueException::createPositiveInteger('Length');
        }

        $this->length = $length;
    }

    public function setContent(ContentInterface $content): void
    {
        $this->content = $content;
    }

    public function addFilters(FilterInterface ...$filters): void
    {
        foreach ($filters as $filter) {
            $this->filters[] = $filter;
        }
    }

    /**
     * @throws InvalidObjectTypeException
     * @throws InvalidArgumentValueException
     */
    public function setDecodedLength(NumericObject|IndirectReference $decodedLength): void
    {
        $this->assertType($decodedLength, NumericObject::class);

        if ($decodedLength->getValue() < 0) {
            throw InvalidArgumentValueException::createPositiveInteger('DL (decoded length)');
        }

        $this->decodedLength = $decodedLength;
    }

    /**
     * @throws MissingRequiredArgumentException
     * @throws InvalidArgumentValueException
     */
    public function compile(): string
    {
        $content = $this->content->render();
        if (!isset($this->length)) {
            $this->length = new NumericObject(strlen($content));
        }

        return <<<COMPILED
        {$this->getValue()->compile()}
        stream
        {$content}
        endstream
        COMPILED;
    }

    public function getValue(): ObjectInterface
    {
        // TODO: Support all other optional parameters.
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Length'), $this->length);

        $filters = new ArrayObject();
        $decodeParams = new ArrayObject();
        foreach ($this->filters as $filter) {
            $filters->push($filter->getName());

            $params = $filter->getDecodeParams();
            if (!$params->isEmpty()) {
                $decodeParams->push($params);
            }
        }

        $filtersCount = $filters->count();
        if ($filtersCount !== 0) {
            $dictionary->set(new NameObject('Filter'), ($filtersCount === 1) ? $filters->pop() : $filters);
        }

        $decodeParamsCount = $decodeParams->count();
        if ($decodeParamsCount !== 0) {
            $dictionary->set(
                new NameObject('DecodeParams'),
                ($decodeParamsCount === 1) ? $decodeParams->pop() : $decodeParams
            );
        }

        return $dictionary;
    }

    public function getConstraints(): array
    {
        $constraints = [];
        if (isset($this->decodedLength)) {
            $constraints[] = new AvailableSince('DL (Decode Length) in a stream', Version::PDF_1_5);
        }

        return $constraints;
    }
}
