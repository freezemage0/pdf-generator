<?php

namespace Freezemage\PdfGenerator\Structure\Body;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Exception\MissingRequiredArgumentException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;

final class DocumentCatalog implements ObjectInterface
{
    private IndirectReference $rootPage;

    public function hasRootPage(): bool
    {
        return isset($this->rootPage);
    }

    /**
     * @throws InvalidObjectTypeException
     */
    public function setRootPage(IndirectReference $rootPage): void
    {
        if (!$rootPage->isOfType(PageTree::class)) {
            throw InvalidObjectTypeException::create('Root Page', 'page tree');
        }

        $this->rootPage = $rootPage;
    }

    /**
     * @throws MissingRequiredArgumentException
     */
    public function compile(): string
    {
        return $this->getValue()->compile();
    }

    /**
     * @throws MissingRequiredArgumentException
     */
    public function getValue(): DictionaryObject
    {
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Type'), new NameObject('Catalog'));

        if (!$this->hasRootPage()) {
            throw new MissingRequiredArgumentException('Document Catalog must have at root page tree');
        }
        $dictionary->set(new NameObject('Pages'), $this->rootPage);

        return $dictionary;
    }
}
