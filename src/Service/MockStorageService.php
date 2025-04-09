<?php

namespace OneToMany\StorageBundle\Service;

use OneToMany\StorageBundle\Record\LocalFileRecord;
use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;

final readonly class MockStorageService implements StorageServiceInterface
{
    public function __construct(private string $bucket)
    {
    }

    /**
     * @see OneToMany\StorageBundle\StorageServiceInterface
     */
    public function download(DownloadFileRequest $request): LocalFileRecord
    {
        throw new \RuntimeException('Not implemented!');
    }

    /**
     * @see OneToMany\StorageBundle\StorageServiceInterface
     */
    public function upload(UploadFileRequest $request): RemoteFileRecord
    {
        $url = \vsprintf('https://remote-files.mock/%s/%s', [
            $this->bucket, $request->remoteKey,
        ]);

        return new RemoteFileRecord($url);
    }
}
