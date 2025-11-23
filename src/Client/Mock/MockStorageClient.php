<?php

namespace OneToMany\StorageBundle\Client\Mock;

use OneToMany\StorageBundle\Client\GenerateUrlTrait;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Exception\RuntimeException;

use function vsprintf;

class MockStorageClient implements StorageClientInterface
{
    use GenerateUrlTrait;

    public function __construct(
        private string $bucket,
        private ?string $customUrl = null,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function download(DownloadFileRequestInterface $request): DownloadedFileResponseInterface
    {
        throw new RuntimeException('Not implemented!');
    }

    /**
     * {@inheritdoc}
     */
    public function upload(UploadFileRequest $request): RemoteFileRecord
    {
        $url = vsprintf('https://mock-storage.service/%s/%s', [
            $this->bucket, $request->key,
        ]);

        return new RemoteFileRecord($this->generateUrl($url, $this->customUrl, $request->key));
    }
}
