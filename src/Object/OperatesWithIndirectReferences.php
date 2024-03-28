<?php

namespace Freezemage\PdfGenerator\Object;

use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;

trait OperatesWithIndirectReferences
{
    /**
     * @throws InvalidObjectTypeException
     */
    protected function validateType(ObjectInterface $object, string $expectedType, string $objectName = ''): void
    {
        if ($object instanceof IndirectReference) {
            $object = $object->object;
        }

        if (!($object instanceof $expectedType)) {
            throw InvalidObjectTypeException::create($objectName ?: $object::class, $expectedType);
        }
    }
}
