<?php

namespace Freezemage\PdfGenerator\Object\Collection;

use ArrayIterator;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use IteratorAggregate;
use Traversable;

/**
 * @template-implements IteratorAggregate<int, ObjectInterface>
 */
final class ArrayObject implements ObjectInterface, IteratorAggregate
{
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

    public function isEmpty(): bool
    {
        return empty($this->objects);
    }

    public function compile(): string
    {
        $objects = array_map(static fn (ObjectInterface $object): string => $object->compile(), $this->objects);
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
