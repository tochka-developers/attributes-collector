<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector\Scanner;

use Tochka\AttributesCollector\AttributeCollection;

interface AttributeScannerInterface
{
    public function getAttributes(): AttributeCollection;
}
