<?php

namespace OneToMany\StorageBundle\Storage;

use Aws\S3\S3Client;
use OneToMany\StorageBundle\Storage\Exception\DownloadingFileFailedException;
use OneToMany\StorageBundle\Storage\Exception\LocalFileNotReadableException;
use OneToMany\StorageBundle\Storage\Exception\UploadingFileFailedException;
use OneToMany\StorageBundle\Storage\Record\LocalFileRecord;
use OneToMany\StorageBundle\Storage\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Storage\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Storage\Request\UploadFileRequest;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
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

            $filePath = $this->filesystem->tempnam(
                \sys_get_temp_dir(), '__1n__file_'
            );
        } catch (\Exception $e) {
            throw new DownloadingFileFailedException($request->key, $e);
        }

        try {
            $this->filesystem->dumpFile(
                $filePath, $body->getContents()
            );
        } catch (\Exception $e) {
            if ($this->filesystem->exists($filePath)) {
                $this->filesystem->remove($filePath);
            }

            throw new DownloadingFileFailedException($request->key, $e);
        }

        return new LocalFileRecord($filePath);
    }

    /**
     * @see OneToMany\StorageBundle\Storage\StorageServiceInterface
     */
    public function upload(UploadFileRequest $request): RemoteFileRecord
    {
        try {
            if (!$this->filesystem->exists($request->filePath)) {
                throw new LocalFileNotReadableException($request->filePath);
            }
        } catch (IOExceptionInterface $e) {
            throw new LocalFileNotReadableException($request->filePath, $e);
        }

        try {
            $accessControl = $this->resolveAcl(...[
                'isPublic' => $request->isPublic,
            ]);

            $contentType = $this->resolveContentType(...[
                'filePath' => $request->filePath,
                'contentType' => $request->contentType,
            ]);

            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'ACL' => $accessControl,
                'Key' => $request->remoteKey,
                'Content-Type' => $contentType,
                'SourceFile' => $request->filePath,
            ]);

            if (!\is_string($url = $result->get('ObjectURL'))) {
                throw new \RuntimeException('no url generated');
            }
        } catch (\Exception $e) {
            throw new UploadingFileFailedException($e);
        }

        return new RemoteFileRecord($request->getUrl($url));
    }

    private function resolveAcl(bool $isPublic): string
    {
        return $isPublic ? 'public-read' : 'private';
    }

    private function resolveContentType(string $filePath, ?string $contentType): string
    {
        if (!empty($contentType)) {
            return $contentType;
        }

        $contentType = null;

        if (\function_exists('mime_content_type')) {
            $contentType = \mime_content_type($filePath);
        }

        return $contentType ?: 'application/octet-stream';
    }
}
