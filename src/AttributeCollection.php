<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector;

/**
 * @api
 * @template TAttribute of object
 * @implements \IteratorAggregate<array-key, AttributeMeta<TAttribute>>
 */
readonly class AttributeCollection implements \Countable, \IteratorAggregate
{
    /**
     * @param list<AttributeMeta<TAttribute>> $attributes
     */
    public function __construct(
        private array $attributes = [],
    ) {}

    /**
     * @template TFindAttribute of object
     * @param class-string<TFindAttribute> $attributeName
     * @return AttributeCollection<TFindAttribute>
     */
    public function byAttributeName(string $attributeName): AttributeCollection
    {
        /** @var AttributeCollection<TFindAttribute> */
        return $this->filter(
            fn(AttributeMeta $item) => $item->attributeClassName === $attributeName,
        );
    }

    /**
     * @template TFindAttribute of object
     * @param class-string<TFindAttribute> $attributeName
     * @return AttributeCollection<TFindAttribute>
     */
    public function byAttributeInstanceOf(string $attributeName): AttributeCollection
    {
        /** @var AttributeCollection<TFindAttribute> */
        return $this->filter(
            fn(AttributeMeta $item) => is_a($item->attributeClassName, $attributeName, true),
        );
    }

    public function classAttributes(): AttributeCollection
    {
        return $this->filter(
            fn(AttributeMeta $item) => $item->className !== null
                && $item->methodName === null
                && $item->parameterName === null
                && $item->propertyName === null,
        );
    }

    public function classMethodAttributes(): AttributeCollection
    {
        return $this->filter(
            fn(AttributeMeta $item) => $item->className !== null
                && $item->methodName !== null
                && $item->parameterName === null
                && $item->propertyName === null,
        );
    }

    public function classMethodParameterAttributes(): AttributeCollection
    {
        return $this->filter(
            fn(AttributeMeta $item) => $item->className !== null
                && $item->methodName !== null
                && $item->parameterName !== null
                && $item->propertyName === null,
        );
    }

    public function classPropertyAttributes(): AttributeCollection
    {
        return $this->filter(
            fn(AttributeMeta $item) => $item->className !== null
                && $item->methodName === null
                && $item->parameterName === null
                && $item->propertyName !== null,
        );
    }

    public function byClassName(string $className): AttributeCollection
    {
        return $this->filter(
            fn(AttributeMeta $item) => $item->className === $className,
        );
    }

    public function byMethodName(string $className, string $methodName): AttributeCollection
    {
        return $this->filter(
            fn(AttributeMeta $item) => $item->className === $className && $item->methodName === $methodName,
        );
    }

    public function byPropertyName(string $className, string $propertyName): AttributeCollection
    {
        return $this->filter(
            fn(AttributeMeta $item) => $item->className === $className && $item->propertyName === $propertyName,
        );
    }

    public function byMethodParameterName(
        string $className,
        string $methodName,
        string $parameterName,
    ): AttributeCollection {
        return $this->filter(
            fn(AttributeMeta $item) => $item->className === $className
                && $item->methodName === $methodName
                && $item->parameterName === $parameterName,
        );
    }

    /**
     * @param callable(AttributeMeta):bool $callback
     * @return AttributeCollection
     */
    public function filter(callable $callback): AttributeCollection
    {
        return new self(
            array_values(
                array_filter($this->attributes, $callback),
            ),
        );
    }

    public function empty(): bool
    {
        return count($this->attributes) === 0;
    }

    public function first(): ?AttributeMeta
    {
        return reset($this->attributes);
    }

    public function getIterator(): \Traversable
    {
        yield from $this->attributes;
    }

    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * @template TMergedAttribute of object
     * @param AttributeCollection<TMergedAttribute> $attributes
     * @return AttributeCollection<TAttribute|TMergedAttribute>
     */
    public function merge(AttributeCollection $attributes): AttributeCollection
    {
        return new self(array_merge($this->attributes, $attributes->attributes));
    }

    /**
     * @template TAddedAttribute of object
     * @param AttributeMeta<TAddedAttribute> $attributeMeta
     * @return AttributeCollection<TAttribute|TAddedAttribute>
     */
    public function add(AttributeMeta $attributeMeta): AttributeCollection
    {
        /** @psalm-suppress InvalidArgument */
        return new self(array_merge($this->attributes, [$attributeMeta]));
    }
}
