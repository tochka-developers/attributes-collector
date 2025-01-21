<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector\Scanner;

use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;
use Tochka\AttributesCollector\Ast;
use Tochka\AttributesCollector\AttributeCollection;
use Tochka\AttributesCollector\AttributeMeta;

readonly class AttributeScanner implements AttributeScannerInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     * @param list<string> $scanPaths
     */
    public function __construct(
        private array $scanPaths = [],
    ) {}

    /**
     * @throws \ReflectionException
     * @throws \LogicException
     * @throws DirectoryNotFoundException
     */
    public function getAttributes(): AttributeCollection
    {
        $classes = $this->findClasses();

        return new AttributeCollection($this->findAttributes($classes));
    }

    /**
     * @return list<class-string>
     * @throws \LogicException
     * @throws DirectoryNotFoundException
     */
    private function findClasses(): array
    {
        $phpFiles = Finder::create()->in($this->scanPaths)->files()->name('*.php');

        $parser = new Ast();

        $classes = [];
        foreach ($phpFiles as $file) {
            try {
                $stmts = $parser->parse($file->getContents());
                if ($stmts === null || !$className = $parser->parseClassByStmts($stmts)) {
                    continue;
                }

                $classes[] = $className;
            } catch (\Throwable $e) {
                echo sprintf(
                    "\033[31m%s\033[0m",
                    '[ERROR] AttributeScanner collecting class reflections failed. ' . PHP_EOL .
                        "File: {$file->getRealPath()}." . PHP_EOL .
                        'Exception: ' . $e->getMessage(),
                ) . PHP_EOL;
            }
        }

        return $classes;
    }

    /**
     * @param list<class-string> $classNames
     * @return list<AttributeMeta<object>>
     * @throws \ReflectionException
     */
    private function findAttributes(array $classNames): array
    {
        $attributes = [];

        foreach ($classNames as $className) {
            $classReflection = new \ReflectionClass($className);

            foreach ($classReflection->getAttributes() as $attribute) {
                /** @var class-string $attributeClassName */
                $attributeClassName = $attribute->getName();
                $attributes[] = new AttributeMeta(
                    attributeClassName: $attributeClassName,
                    instance: $attribute->newInstance(),
                    className: $className,
                );
            }

            foreach ($classReflection->getProperties() as $propertyReflection) {
                foreach ($propertyReflection->getAttributes() as $attribute) {
                    /** @var class-string $attributeClassName */
                    $attributeClassName = $attribute->getName();
                    $attributes[] = new AttributeMeta(
                        attributeClassName: $attributeClassName,
                        instance: $attribute->newInstance(),
                        className: $className,
                        propertyName: $propertyReflection->getName(),
                    );
                }
            }

            foreach ($classReflection->getMethods() as $methodReflection) {
                foreach ($methodReflection->getAttributes() as $attribute) {
                    /** @var class-string $attributeClassName */
                    $attributeClassName = $attribute->getName();
                    $attributes[] = new AttributeMeta(
                        attributeClassName: $attributeClassName,
                        instance: $attribute->newInstance(),
                        className: $className,
                        methodName: $methodReflection->getName(),
                    );
                }

                foreach ($methodReflection->getParameters() as $parameterReflection) {
                    foreach ($parameterReflection->getAttributes() as $attribute) {
                        /** @var class-string $attributeClassName */
                        $attributeClassName = $attribute->getName();
                        $attributes[] = new AttributeMeta(
                            attributeClassName: $attributeClassName,
                            instance: $attribute->newInstance(),
                            className: $className,
                            methodName: $methodReflection->getName(),
                            parameterName: $parameterReflection->getName(),
                        );
                    }
                }
            }
        }

        return $attributes;
    }
}
