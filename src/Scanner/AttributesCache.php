<?php

declare(strict_types=1);

namespace Tochka\AttributesCollector\Scanner;

use Tochka\AttributesCollector\AttributeCollection;

/**
 * @api
 */
readonly class AttributesCache implements AttributesCacheInterface
{
    public function __construct(
        private string $cachePath,
        private string $fileName = 'attributes.cache',
    ) {}

    /**
     * @throws \RuntimeException
     */
    public function has(): bool
    {
        return file_exists($this->path());
    }

    /**
     * @throws \RuntimeException
     */
    public function get(): AttributeCollection
    {
        if (!$this->has()) {
            throw new \RuntimeException('Cache not exists');
        }

        /** @psalm-suppress UnresolvableInclude */
        return require $this->path();
    }

    /**
     * @throws \RuntimeException
     */
    public function set(AttributeCollection $collection): void
    {
        file_put_contents($this->path(), '<?php return ' . var_export($collection, true) . ';' . PHP_EOL);
    }

    /**
     * @throws \RuntimeException
     */
    public function clear(): void
    {
        if (file_exists($this->path())) {
            unlink($this->path());
        }
    }

    /**
     * @throws \RuntimeException
     */
    private function path(): string
    {
        if (!is_dir($this->cachePath) && !mkdir($this->cachePath) && !is_dir($this->cachePath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->cachePath));
        }

        return $this->cachePath . DIRECTORY_SEPARATOR . $this->fileName;
    }
}
