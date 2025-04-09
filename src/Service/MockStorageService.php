<?php

namespace OneToMany\StorageBundle\Service;

use OneToMany\StorageBundle\Record\LocalFileRecord;
use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;

use function vsprintf;

final readonly class MockStorageService implements StorageServiceInterface
{
    use GenerateUrlTrait;

    public function __construct(
        private string $bucket,
        private ?string $customUrl = null,
    ) {
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
        $canonicalUrl = vsprintf('https://mock-storage.service/%s/%s', [
            $this->bucket, $request->remoteKey,
        ]);

        $objectUrl = $this->generateUrl(...[
            'canonicalUrl' => $canonicalUrl,
            'customUrl' => $this->customUrl,
            'remoteKey' => $request->remoteKey,
        ]);

        return new RemoteFileRecord($objectUrl);
    }
}
