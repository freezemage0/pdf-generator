<?php

namespace Freezemage\PdfGenerator\Object\Collection;

use ArrayIterator;
use Freezemage\PdfGenerator\Object\NullObject;
use Freezemage\PdfGenerator\Object\ObjectInterface;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use IteratorAggregate;
use Traversable;

/**
 * @template-implements IteratorAggregate<int, array{0: NameObject, 1: ObjectInterface}>
 */
final class DictionaryObject implements ReferableObjectInterface, IteratorAggregate
{
    use ReferableObjectImplementation;

    private static int $sharedIndentationLevel = 0;

    /** @var array<string, KeyPair> */
    private array $entries = [];

    public function get(string $key): ?KeyPair
    {
        return $this->entries[$key] ?? null;
    }

    public function isEmpty(): bool
    {
        return empty($this->entries);
    }

    public function set(NameObject $key, ObjectInterface $value): void
    {
        $this->entries[$key->name] = new KeyPair($key, $value);
    }

    public function remove(NameObject $key): void
    {
        if (!isset($this->entries[$key->name])) {
            return;
        }

        unset($this->entries[$key->name]);
    }

    public function compile(): string
    {
        $compiled = ['<<'];

        DictionaryObject::$sharedIndentationLevel += 1;
        $indentation = str_repeat(' ', DictionaryObject::$sharedIndentationLevel * 4);
        foreach ($this->entries as $pair) {
            if ($pair->value instanceof NullObject) {
                continue;
            }

            $compiled[] = "{$indentation}{$pair->key->compile()} {$pair->value->compile()}";
        }
        DictionaryObject::$sharedIndentationLevel -= 1;
        $indentation = str_repeat(' ', DictionaryObject::$sharedIndentationLevel * 4);

        $compiled[] = "{$indentation}>>";

        return implode("\n", $compiled);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->entries);
    }

    public function getValue(): array
    {
        return $this->entries;
    }
}
