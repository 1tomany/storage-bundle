<?php

namespace App\File\Service\Storage;

use App\File\Service\Storage\Record\LocalFileRecord;
use App\File\Service\Storage\Record\RemoteFileRecord;
use App\File\Service\Storage\Request\DownloadFileRequest;
use App\File\Service\Storage\Request\UploadFileRequest;

final readonly class MockStorageService implements StorageServiceInterface
{
    public function __construct(private string $bucket)
    {
    }

    /**
     * @see App\File\Service\Storage\StorageServiceInterface
     */
    public function download(DownloadFileRequest $request): LocalFileRecord
    {
        throw new \RuntimeException('Not implemented!');
    }

    /**
     * @see App\File\Service\Storage\StorageServiceInterface
     */
    public function upload(UploadFileRequest $request): RemoteFileRecord
    {
        $url = vsprintf('https://fieldsheet-files.mock/%s/%s', [
            $this->bucket, $request->key,
        ]);

        return new RemoteFileRecord($url);
    }
}
