<?php

namespace Freezemage\PdfGenerator\Object\Collection;

use ArrayIterator;
use Freezemage\PdfGenerator\Encoding\CharacterSet;
use Freezemage\PdfGenerator\Exception\UnderflowException;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use IteratorAggregate;
use Traversable;

/**
 * @template-implements IteratorAggregate<int, ObjectInterface>
 */
final class ArrayObject implements ReferableObjectInterface, IteratorAggregate
{
    use ReferableObjectImplementation;

    private array $objects;

    public function __construct(ObjectInterface ...$objects) {
        $this->objects = $objects;
    }

    public function push(ObjectInterface ...$objects): void
    {
        foreach ($objects as $object) {
            $this->objects[] = $object;
        }
    }

    /**
     * @throws UnderflowException
     */
    public function pop(): ObjectInterface
    {
        if ($this->isEmpty()) {
            throw UnderflowException::create();
        }

        return array_pop($this->objects);
    }

    /**
     * @throws UnderflowException
     */
    public function top(): ObjectInterface
    {
        $object = $this->pop();
        $this->push($object);

        return $object;
    }

    public function isEmpty(): bool
    {
        return empty($this->objects);
    }

    public function count(): int
    {
        return count($this->objects);
    }

    public function compile(): string
    {
        $objects = array_map(
            static fn(ObjectInterface $object): string => $object->compile(),
            $this->objects
        );

        return '[' . implode(' ', $objects) . ']';
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->objects);
    }

    public function getValue(): mixed
    {
        return $this->objects;
    }
}
