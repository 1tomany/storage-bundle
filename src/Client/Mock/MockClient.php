<?php

namespace OneToMany\StorageBundle\Client\Mock;

use OneToMany\StorageBundle\Client\BaseClient;
use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Request\DeleteRequest;
use OneToMany\StorageBundle\Request\DownloadRequest;
use OneToMany\StorageBundle\Request\UploadRequest;
use OneToMany\StorageBundle\Response\DeletedFileResponse;
use OneToMany\StorageBundle\Response\UploadedFileResponse;

use function vsprintf;

class MockClient extends BaseClient
{
    /**
     * @see OneToMany\StorageBundle\Contract\Client\StorageClientInterface
     */
    public function upload(UploadRequest $request): UploadedFileResponseInterface
    {
        $url = vsprintf('https://mock-storage.service/%s/%s', [
            $this->getBucket(), $request->getKey(),
        ]);

        return new UploadedFileResponse($this->generateUrl($url, $this->getCustomUrl(), $request->getKey()));
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\StorageClientInterface
     */
    public function download(DownloadRequest $request): DownloadedFileResponseInterface
    {
        throw new RuntimeException('Not implemented!');
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\StorageClientInterface
     */
    public function delete(DeleteRequest $request): DeletedFileResponseInterface
    {
        return new DeletedFileResponse($request->getKey());
    }
}
