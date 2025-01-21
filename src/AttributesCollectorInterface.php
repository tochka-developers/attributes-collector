<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector;

/**
 * @api
 */
interface AttributesCollectorInterface
{
    public function getAttributes(): AttributeCollection;
}
