<?php

namespace OneToMany\StorageBundle\Storage;

use OneToMany\StorageBundle\Storage\Exception\DownloadingFileFailedException;
use OneToMany\StorageBundle\Storage\Exception\LocalFileNotReadableException;
use OneToMany\StorageBundle\Storage\Exception\UploadingFileFailedException;
use OneToMany\StorageBundle\Storage\Record\LocalFileRecord;
use OneToMany\StorageBundle\Storage\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Storage\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Storage\Request\UploadFileRequest;
use Aws\S3\S3Client;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Filesystem;

final readonly class AmazonStorageService implements StorageServiceInterface
{
    private Filesystem $filesystem;

    public function __construct(
        private S3Client $s3Client,
        private string $bucket,
    ) {
        $this->filesystem = new Filesystem();
    }

    /**
     * @see OneToMany\StorageBundle\Storage\StorageServiceInterface
     */
    public function download(DownloadFileRequest $request): LocalFileRecord
    {
        try {
            $file = $this->s3Client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $request->key,
            ]);

            $body = $file->get('Body');

            if (!$body instanceof StreamInterface) {
                throw new \RuntimeException('An error occurred when attempting to stream the contents of the downloaded file.');
            }

            $path = $this->filesystem->tempnam(
                sys_get_temp_dir(), '__fs__file_'
            );

            $this->filesystem->dumpFile(
                $path, $body->getContents()
            );
        } catch (\Exception $e) {
            throw new DownloadingFileFailedException($request->key, $e);
        }

        return new LocalFileRecord($path);
    }

    /**
     * @see OneToMany\StorageBundle\Storage\StorageServiceInterface
     */
    public function upload(UploadFileRequest $request): RemoteFileRecord
    {
        try {
            $fileContents = @file_get_contents($request->path);

            if (false === $fileContents) {
                throw new LocalFileNotReadableException($request->path);
            }

            // Resolve Access Control List
            $accessControl = $this->resolveAcl(...[
                'public' => $request->isPublic,
            ]);

            // Resolve Storage Options
            $options = $this->resolveOptions(...[
                'mediaType' => $request->media,
            ]);

            // Upload File to Amazon S3
            $this->s3Client->upload(...[
                'bucket' => $this->bucket,
                'key' => $request->key,
                'body' => $fileContents,
                'acl' => $accessControl,
                'options' => $options,
            ]);

            // Fetch URL to Confirm Upload
            $url = $this->s3Client->getObjectUrl(
                $this->bucket, $request->key
            );
        } catch (\Exception $e) {
            throw new UploadingFileFailedException($e);
        } finally {
            if (isset($fileContents)) {
                unset($fileContents);
            }
        }

        return new RemoteFileRecord($url);
    }

    private function resolveAcl(bool $public): string
    {
        return $public ? 'public-read' : 'private';
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function resolveOptions(string $mediaType): array
    {
        return ['params' => ['ContentType' => $mediaType]];
    }
}
