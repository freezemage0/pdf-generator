<?php

namespace Freezemage\PdfGenerator\Structure\Body;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;

final class DocumentCatalog implements ObjectInterface
{
    private DictionaryObject $dictionary;

    public function __construct()
    {
        $this->dictionary = new DictionaryObject();
        $this->dictionary->set(new NameObject('Type'), new NameObject('Catalog'));
    }

    public function hasRootPage(): bool
    {
        return $this->dictionary->get('Pages') !== null;
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setRootPage(IndirectReference $rootPage): void
    {
        if (!$rootPage->isOfType(PageTree::class)) {
            throw InvalidObjectTypeException::create('Page', 'dictionary');
        }

        $this->dictionary->set(new NameObject('Pages'), $rootPage);
    }

    public function compile(): string
    {
        return $this->dictionary->compile();
    }

    public function getValue(): DictionaryObject
    {
        return $this->dictionary;
    }
}
