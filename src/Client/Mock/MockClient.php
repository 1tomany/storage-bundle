<?php

namespace OneToMany\StorageBundle\Client\Mock;

use OneToMany\StorageBundle\Client\BaseClient;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Request\DeleteRequest;
use OneToMany\StorageBundle\Request\DownloadRequest;
use OneToMany\StorageBundle\Request\UploadRequest;
use OneToMany\StorageBundle\Response\DeleteResponse;
use OneToMany\StorageBundle\Response\DownloadResponse;
use OneToMany\StorageBundle\Response\UploadResponse;

use function vsprintf;

class MockClient extends BaseClient
{
    /**
     * @see OneToMany\StorageBundle\Contract\Client\ClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $url = vsprintf('https://mock-storage.service/%s/%s', [
            $this->getBucket(), $request->getKey(),
        ]);

        return new UploadResponse($this->generateUrl($url, $this->getCustomUrl(), $request->getKey()));
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\ClientInterface
     */
    public function download(DownloadRequest $request): DownloadResponse
    {
        throw new RuntimeException('Not implemented!');
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\ClientInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        return new DeleteResponse($request->getKey());
    }
}
