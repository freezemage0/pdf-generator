<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Exception\UnderflowException;
use Freezemage\PdfGenerator\Object\Collection\ArrayObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Stream;

final class Contents implements ObjectInterface
{
    private ArrayObject $streams;

    public function __construct()
    {
        $this->streams = new ArrayObject();
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function addStream(IndirectReference|Stream $stream): void
    {
        if ($stream instanceof IndirectReference && !$stream->isOfType(Stream::class)) {
            throw InvalidObjectTypeException::create('Stream', 'content stream');
        }

        $this->streams->push($stream);
    }

    /**
     * @throws MissingRequiredArgumentException
     * @throws UnderflowException
     */
    public function getValue(): ObjectInterface
    {
        if ($this->streams->isEmpty()) {
            throw new MissingRequiredArgumentException('Page Contents must have at least one content stream.');
        }

        return $this->streams->count() === 1 ? $this->streams->top() : $this->streams;
    }

    /**
     * @throws MissingRequiredArgumentException
     * @throws UnderflowException
     */
    public function compile(): string
    {
        return $this->getValue()->compile();
    }
}