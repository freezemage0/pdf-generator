<?php

namespace Freezemage\PdfGenerator\Object;

use Freezemage\PdfGenerator\Exception\InvalidArgumentValueException;
use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;
use Freezemage\PdfGenerator\Object\Stream\ContentInterface;
use Freezemage\PdfGenerator\Object\Stream\FilterInterface;

class Stream implements ReferableObjectInterface
{
    use ReferableObjectImplementation;

    /** @var array<FilterInterface> */
    private array $filters = [];
    private NumericObject|IndirectReference $length;
    private ContentInterface $content;

    /**
     * @throws InvalidObjectTypeException
     */
    public function setLength(NumericObject|IndirectReference $length): void
    {
        if ($length instanceof IndirectReference && !$length->isOfType(NumericObject::class)) {
            throw InvalidObjectTypeException::create('Length', 'numeric');
        }

        $this->length = $length;
    }

    public function setContent(ContentInterface $content): void
    {
        $this->content = $content;
    }

    public function addFilter(FilterInterface $filter): void
    {
        $this->filters[] = $filter;
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

        // TODO: Support all other optional parameters.
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Length'), $this->length);

        return <<<COMPILED
        {$dictionary->compile()}
        stream
        {$content}
        endstream
        COMPILED;
    }

    public function getValue(): ObjectInterface
    {
        return new NullObject();
    }
}
