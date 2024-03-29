<?php

namespace Freezemage\PdfGenerator\Structure\Body\Page\Resources\Font;

use Freezemage\PdfGenerator\Exception\InvalidArgumentValueException;
use Freezemage\PdfGenerator\Exception\InvalidObjectTypeException;
use Freezemage\PdfGenerator\Object\Collection\DictionaryObject;
use Freezemage\PdfGenerator\Object\IndirectReference;
use Freezemage\PdfGenerator\Object\OperatesWithIndirectReferences;
use Freezemage\PdfGenerator\Object\ReferableObjectImplementation;
use Freezemage\PdfGenerator\Object\ReferableObjectInterface;
use Freezemage\PdfGenerator\Object\Scalar\HexadecimalStringObject;
use Freezemage\PdfGenerator\Object\Scalar\NameObject;
use Freezemage\PdfGenerator\Object\Scalar\NumericObject;
use Freezemage\PdfGenerator\Structure\Header\Version;
use Freezemage\PdfGenerator\Version\Constraint\AvailableSince;
use Freezemage\PdfGenerator\Version\VersionDependentInterface;

final class Descriptor implements ReferableObjectInterface, VersionDependentInterface
{
    use OperatesWithIndirectReferences;
    use ReferableObjectImplementation;

    private NameObject|IndirectReference $fontName;
    private HexadecimalStringObject|IndirectReference $fontFamily;
    private NumericObject|IndirectReference $fontWeight;
    /** @var array<DescriptorFlag> */
    private array $flags = [];

    /**
     * @throws InvalidObjectTypeException
     */
    public function setFontName(NameObject|IndirectReference $fontName): Descriptor
    {
        $this->assertType($fontName, NameObject::class, 'name');
        $this->fontName = $fontName;

        return $this;
    }

    /**
     * @throws InvalidArgumentValueException
     */
    public function addFlags(DescriptorFlag ...$descriptorFlags): Descriptor
    {
        foreach ($descriptorFlags as $flag) {
            if ($this->hasFlag($flag)) {
                continue;
            }
            $this->flags[] = $flag;
        }

        if ($this->hasFlag(DescriptorFlag::SYMBOLIC) && $this->hasFlag(DescriptorFlag::NON_SYMBOLIC)) {
            throw new InvalidArgumentValueException('Conflicting Font Descriptor flags detected.');
        }

        return $this;
    }

    public function hasFlag(DescriptorFlag $flag): bool
    {
        return in_array($flag, $this->flags, true);
    }

    public function getValue(): DictionaryObject
    {
        $dictionary = new DictionaryObject();
        $dictionary->set(new NameObject('Type'), new NameObject('FontDescriptor'));

        $bitmask = 0;
        foreach ($this->flags as $flag) {
            $bitmask |= $flag->toBinary();
        }
        $dictionary->set(new NameObject('Flags'), new NumericObject($bitmask));

        return $dictionary;
    }

    public function compile(): string
    {
        return $this->getValue()->compile();
    }

    public function getConstraints(): array
    {
        $constraints = [];
        if (isset($this->fontWeight)) {
            $constraints[] = new AvailableSince(
                'FontWeight entry for FontDescriptor dictionary',
                Version::PDF_1_5
            );
        }

        return $constraints;
    }
}