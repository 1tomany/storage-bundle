<?php

namespace OneToMany\StorageBundle\Client\Amazon;

use Aws\S3\S3ClientInterface;
use OneToMany\StorageBundle\Client\BaseClient;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Request\DeleteRequest;
use OneToMany\StorageBundle\Request\DownloadRequest;
use OneToMany\StorageBundle\Request\UploadRequest;
use OneToMany\StorageBundle\Response\DeleteResponse;
use OneToMany\StorageBundle\Response\DownloadResponse;
use OneToMany\StorageBundle\Response\UploadResponse;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Exception\ExceptionInterface as FilesystemExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

use function is_string;
use function sprintf;

class AmazonClient extends BaseClient
{
    public function __construct(
        private S3ClientInterface $s3Client,
        string $bucket,
        ?string $customUrl,
    )
    {
        parent::__construct($bucket, $customUrl);
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\ClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        try {
            $resolveAcl = function (UploadRequest $request): string {
                return $request->isPublic() ? 'public-read' : 'private';
            };

            $result = $this->s3Client->putObject([
                'Bucket' => $this->getBucket(),
                'Key' => $request->getKey(),
                'ACL' => $resolveAcl($request),
                'SourceFile' => $request->getPath(),
                'Content-Type' => $request->getFormat(),
            ]);

            $url = $result->get('ObjectURL');
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf('Uploading the file "%s" to "%s" failed.', $request->getPath(), $request->getKey()), previous: $e);
        }

        if (!is_string($url) || empty($url)) {
            throw new RuntimeException(sprintf('Uploading the file "%s" to "%s" failed because an invalid URL was returned.', $request->getPath(), $request->getKey()));
        }

        $url = $this->generateUrl($url, $this->getCustomUrl(), $request->getKey());

        return new UploadResponse($url);
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\ClientInterface
     */
    public function download(DownloadRequest $request): DownloadResponse
    {
        $filesystem = new Filesystem();

        try {
            $file = $this->s3Client->getObject([
                'Bucket' => $this->getBucket(),
                'Key' => $request->getKey(),
            ]);

            $body = $file->get('Body');

            if (!$body instanceof StreamInterface) {
                throw new RuntimeException(sprintf('Downloading the file "%s" failed because the remote server failed to stream the contents.', $request->getKey()));
            }
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf('Downloading the file "%s" failed.', $request->getKey()), previous: $e);
        }

        // Resolve the extension for the temporary file
        $ext = Path::getExtension($request->getKey(), true);

        try {
            $path = $filesystem->tempnam($request->getDirectory(), $request::PREFIX, $ext ?: ".{$ext}");
        } catch (FilesystemExceptionInterface $e) {
            throw new RuntimeException(sprintf('Downloading the file "%s" failed because a temporary file could not be created.', $request->getKey()), previous: $e);
        }

        try {
            $filesystem->dumpFile($path, $body->getContents());
        } catch (FilesystemExceptionInterface $e) {
            if ($filesystem->exists($path)) {
                $filesystem->remove($path);
            }

            throw new RuntimeException(sprintf('Downloading the file "%s" failed because the file contents could not be written to "%s".', $request->getKey(), $path), previous: $e);
        }

        return new DownloadResponse($path);
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\ClientInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        try {
            $this->s3Client->deleteObject([
                'Bucket' => $this->getBucket(),
                'Key' => $request->getKey(),
            ]);
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf('Deleting the file "%s" failed.', $request->getKey()), previous: $e);
        }

        return new DeleteResponse($request->getKey());
    }
}
