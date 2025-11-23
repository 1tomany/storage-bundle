<?php

namespace OneToMany\StorageBundle\Client;

use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Trait\AssertNotEmptyTrait;

abstract class AbstractStorageClient implements StorageClientInterface
{
    use AssertNotEmptyTrait;
    use GenerateUrlTrait;

    /**
     * @var non-empty-string
     */
    protected readonly string $bucket;

    /**
     * @var ?non-empty-string
     */
    protected readonly ?string $customUrl;

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
