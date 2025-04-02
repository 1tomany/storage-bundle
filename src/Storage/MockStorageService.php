<?php

namespace OneToMany\StorageBundle\Storage;

use OneToMany\StorageBundle\Storage\Record\LocalFileRecord;
use OneToMany\StorageBundle\Storage\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Storage\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Storage\Request\UploadFileRequest;

final readonly class MockStorageService implements StorageServiceInterface
{
    public function __construct(private string $bucket)
    {
    }

    /**
     * @see OneToMany\StorageBundle\Storage\StorageServiceInterface
     */
    public function download(DownloadFileRequest $request): LocalFileRecord
    {
        throw new \RuntimeException('Not implemented!');
    }

    /**
     * @see OneToMany\StorageBundle\Storage\StorageServiceInterface
     */
    public function upload(UploadFileRequest $request): RemoteFileRecord
    {
        $url = vsprintf('https://fieldsheet-files.mock/%s/%s', [
            $this->bucket, $request->key,
        ]);

        return new RemoteFileRecord($url);
    }
}
