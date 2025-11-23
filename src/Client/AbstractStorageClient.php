<?php

namespace OneToMany\StorageBundle\Client;

use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Trait\AssertNotEmptyTrait;

abstract class AbstractStorageClient implements StorageClientInterface
{
    use AssertNotEmptyTrait;

    /**
     * @var non-empty-string
     */
    protected string $bucket;

    /**
     * @var ?non-empty-string
     */
    protected ?string $customUrl;

    public function __construct(
        string $bucket,
        ?string $customUrl = null,
    ) {
        $this->bucket = $this->assertNotEmpty($bucket, 'bucket');
        $this->customUrl = trim($customUrl ?? '') ?: null;
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }

    public function getCustomUrl(): ?string
    {
        return $this->customUrl;
    }
}
