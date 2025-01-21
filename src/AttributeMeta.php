<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector;

/**
 * @api
 * @template TAttribute of object
 */
readonly class AttributeMeta
{
    /**
     * @param class-string<TAttribute> $attributeClassName
     * @param TAttribute $instance
     * @param class-string $className
     */
    public function __construct(
        public string $attributeClassName,
        public object $instance,
        public ?string $className = null,
        public ?string $methodName = null,
        public ?string $parameterName = null,
        public ?string $propertyName = null,
    ) {}
}
