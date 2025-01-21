<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector\Scanner;

use Tochka\AttributesCollector\AttributeCollection;

/**
 * @api
 */
readonly class AttributeCacheScanner implements AttributeScannerInterface
{
    public function __construct(
        private AttributeScannerInterface $attributeScanner,
        private AttributesCacheInterface $attributesCache,
    ) {}

    public function getAttributes(): AttributeCollection
    {
        if ($this->attributesCache->has()) {
            return $this->attributesCache->get();
        }

        return $this->attributeScanner->getAttributes();
    }
}
