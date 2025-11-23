<?php

namespace OneToMany\StorageBundle\Client\Mock;

use OneToMany\StorageBundle\Client\GenerateUrlTrait;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Request\DeleteFileRequestInterface;
use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Request\UploadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Response\DeletedFileResponse;
use OneToMany\StorageBundle\Response\UploadedFileResponse;
use OneToMany\StorageBundle\Trait\AssertNotEmptyTrait;

use function trim;
use function vsprintf;

class MockStorageClient implements StorageClientInterface
{
    use AssertNotEmptyTrait;
    use GenerateUrlTrait;

    /**
     * @var non-empty-string
     */
    private string $bucket;

    /**
     * @var ?non-empty-string
     */
    private ?string $customUrl;

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

    public function upload(UploadFileRequestInterface $request): UploadedFileResponseInterface
    {
        $url = vsprintf('https://mock-storage.service/%s/%s', [
            $this->bucket, $request->getKey(),
        ]);

        return new UploadedFileResponse($this->generateUrl($url, $this->getCustomUrl(), $request->getKey()));
    }

    public function download(DownloadFileRequestInterface $request): DownloadedFileResponseInterface
    {
        throw new RuntimeException('Not implemented!');
    }

    public function delete(DeleteFileRequestInterface $request): DeletedFileResponseInterface
    {
        return new DeletedFileResponse();
    }
}
