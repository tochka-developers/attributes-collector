<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector\Scanner;

use Tochka\AttributesCollector\AttributeCollection;

/**
 * @api
 */
interface AttributesCacheInterface
{
    public function has(): bool;

    public function get(): AttributeCollection;

    public function set(AttributeCollection $collection): void;

    public function clear(): void;
}
