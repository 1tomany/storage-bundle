<?php

namespace OneToMany\StorageBundle\Client\Mock;

use OneToMany\StorageBundle\Client\GenerateUrlTrait;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Request\UploadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Response\UploadedFileResponse;

use function vsprintf;

class MockStorageClient implements StorageClientInterface
{
    use GenerateUrlTrait;

    public function __construct(
        private string $bucket,
        private ?string $customUrl = null,
    ) {
    }

    public function download(DownloadFileRequestInterface $request): DownloadedFileResponseInterface
    {
        throw new RuntimeException('Not implemented!');
    }

    public function upload(UploadFileRequestInterface $request): UploadedFileResponseInterface
    {
        $url = vsprintf('https://mock-storage.service/%s/%s', [
            $this->bucket, $request->getKey(),
        ]);

        return new UploadedFileResponse($this->generateUrl($url, $this->customUrl, $request->getKey()));
    }
}
