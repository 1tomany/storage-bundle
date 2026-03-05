<?php

namespace OneToMany\StorageBundle\Client;

use OneToMany\StorageBundle\Client\Trait\GenerateUrlTrait;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;

use function trim;

abstract class BaseClient implements StorageClientInterface
{
    use GenerateUrlTrait;

    /** @var non-empty-string */
    protected string $bucket = self::DEFAULT_BUCKET;

    /** @var ?non-empty-string */
    protected ?string $customUrl = null;

    public const string DEFAULT_BUCKET = '__unknown_bucket__';

    public function __construct(
        ?string $bucket,
        ?string $customUrl = null,
    ) {
        $this->toBucket($bucket);
        $this->usingCustomUrl($customUrl);
    }

    public function toBucket(?string $bucket): static
    {
        $this->bucket = trim($bucket ?? '') ?: self::DEFAULT_BUCKET;

        return $this;
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\StorageClientInterface
     */
    public function getBucket(): string
    {
        return $this->bucket;
    }

    public function usingCustomUrl(?string $customUrl): static
    {
        $this->customUrl = trim($customUrl ?? '') ?: null;

        return $this;
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\StorageClientInterface
     */
    public function getCustomUrl(): ?string
    {
        return $this->customUrl;
    }
}
