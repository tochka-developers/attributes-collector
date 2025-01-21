<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector;

readonly class AttributesCollector implements AttributesCollectorInterface
{
    public function __construct(
        private AttributeCollection $attributeCollection,
    ) {}

    public function getAttributes(): AttributeCollection
    {
        return $this->attributeCollection;
    }
}
