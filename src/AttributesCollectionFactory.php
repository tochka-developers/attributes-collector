<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tochka\AttributesCollector\Scanner\AttributeScannerInterface;

readonly class AttributesCollectionFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AttributesCollectorInterface
    {
        $attributesScanner = $container->get(AttributeScannerInterface::class);

        return new AttributesCollector($attributesScanner->getAttributes());
    }
}
