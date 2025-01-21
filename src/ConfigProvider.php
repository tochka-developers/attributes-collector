<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector;

use Tochka\AttributesCollector\Scanner\AttributesCacheFactory;
use Tochka\AttributesCollector\Scanner\AttributesCacheInterface;
use Tochka\AttributesCollector\Scanner\AttributeScannerFactory;
use Tochka\AttributesCollector\Scanner\AttributeScannerInterface;

/**
 * @api
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                AttributesCollectorInterface::class => AttributesCollectionFactory::class,
                AttributeScannerInterface::class => AttributeScannerFactory::class,
                AttributesCacheInterface::class => AttributesCacheFactory::class,
            ],
        ];
    }
}
