<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector\Scanner;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

readonly class AttributeScannerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AttributeScannerInterface
    {
        return new AttributeCacheScanner(
            $container->get(AttributeScanner::class),
            $container->get(AttributesCacheInterface::class),
        );
    }
}
