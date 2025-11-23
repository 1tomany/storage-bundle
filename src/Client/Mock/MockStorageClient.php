<?php

namespace OneToMany\StorageBundle\Client\Mock;

use OneToMany\StorageBundle\Client\AbstractStorageClient;
use OneToMany\StorageBundle\Contract\Request\DeleteFileRequestInterface;
use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Request\UploadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Response\DeletedFileResponse;
use OneToMany\StorageBundle\Response\UploadedFileResponse;

use function vsprintf;

class MockStorageClient extends AbstractStorageClient
{
    public function upload(UploadFileRequestInterface $request): UploadedFileResponseInterface
    {
        $url = vsprintf('https://mock-storage.service/%s/%s', [
            $this->getBucket(), $request->getKey(),
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
