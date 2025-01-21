<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Parser;
use PhpParser\ParserFactory;

readonly class Ast
{
    private Parser $parser;

    public function __construct()
    {
        $parserFactory = new ParserFactory();
        $this->parser = $parserFactory->create(ParserFactory::ONLY_PHP7);
    }

    /**
     * @param string $code
     * @return array<array-key, Stmt>|null
     */
    public function parse(string $code): ?array
    {
        return $this->parser->parse($code);
    }

    /**
     * @param array $stmts
     * @return class-string|null
     */
    public function parseClassByStmts(array $stmts): ?string
    {
        $namespace = $className = null;
        foreach ($stmts as $stmt) {
            if ($stmt instanceof Namespace_ && $stmt->name) {
                $namespace = $stmt->name->toString();
                foreach ($stmt->stmts as $node) {
                    if (($node instanceof ClassLike) && $node->name) {
                        $className = $node->name->toString();
                        break;
                    }
                }
            }
        }

        if ($namespace === null || $className === null) {
            return null;
        }

        /** @var class-string */
        return $namespace . '\\' . $className;
    }
}
