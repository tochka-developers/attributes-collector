<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector\Scanner;

use Illuminate\Support\Facades\App;

readonly class AttributesCacheFactory
{
    public function __invoke(): AttributesCacheInterface
    {
        if (class_exists(App::class)) {
            $cachePath = App::bootstrapPath();
        } elseif (defined('BASE_PATH')) {
            $cachePath = BASE_PATH . '/runtime/';
        } else {
            $cachePath = '';
        }

        return new AttributesCache($cachePath);
    }
}
