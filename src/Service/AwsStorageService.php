<?php

namespace OneToMany\StorageBundle\Service;

use Aws\S3\S3Client;
use OneToMany\StorageBundle\Exception\DownloadingFileFailedException;
use OneToMany\StorageBundle\Exception\LocalFileNotReadableException;
use OneToMany\StorageBundle\Exception\UploadingFileFailedException;
use OneToMany\StorageBundle\Record\LocalFileRecord;
use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

use function class_exists;
use function file_exists;
use function function_exists;
use function mime_content_type;
use function sprintf;
use function sys_get_temp_dir;
use function trim;

final readonly class AwsStorageService implements StorageServiceInterface
{
    use GenerateUrlTrait;

    private Filesystem $filesystem;

    /**
     * @disregard P1009 Undefined type
     */
    public function __construct(
        // @phpstan-ignore-next-line
        private S3Client $s3Client,
        private string $bucket,
        private ?string $customUrl,
    ) {
        /** @disregard P1009 Undefined type */
        if (!class_exists(S3Client::class)) {
            throw new \LogicException('You cannot use AWS because the AWS SDK is not installed. Try running "composer require aws/aws-sdk-php-symfony".');
        }

        $this->filesystem = new Filesystem();
    }

    /**
     * @see OneToMany\StorageBundle\StorageServiceInterface
     */
    public function download(DownloadFileRequest $request): LocalFileRecord
    {
        try {
            $file = $this->s3Client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $request->remoteKey,
            ]);

            // @phpstan-ignore-next-line
            $body = $file->get('Body');

            if (!$body instanceof StreamInterface) {
                throw new \RuntimeException('The contents of the file could not be streamed.');
            }

            // Attempt to Resolve Extension
            $extension = Path::getExtension(...[
                'path' => $request->remoteKey,
            ]);

            if (!empty($extension = trim($extension))) {
                $extension = sprintf('.%s', $extension);
            }

            $filePath = $this->filesystem->tempnam(sys_get_temp_dir(), DownloadFileRequest::PREFIX, $extension);
        } catch (\Exception $e) {
            throw new DownloadingFileFailedException($request->remoteKey, $e);
        }

        try {
            $this->filesystem->dumpFile($filePath, $body->getContents());
        } catch (\Exception $e) {
            if ($this->filesystem->exists($filePath)) {
                $this->filesystem->remove($filePath);
            }

            throw new DownloadingFileFailedException($request->remoteKey, $e);
        }

        if (!file_exists($filePath)) {
            throw new DownloadingFileFailedException($request->remoteKey);
        }

        return new LocalFileRecord($filePath);
    }

    /**
     * @see OneToMany\StorageBundle\StorageServiceInterface
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

            /** @var non-empty-string $canonicalUrl */
            // @phpstan-ignore-next-line
            $canonicalUrl = $result->get('ObjectURL');
        } catch (\Exception $e) {
            throw new UploadingFileFailedException($e);
        }

        $objectUrl = $this->generateUrl(...[
            'canonicalUrl' => $canonicalUrl,
            'customUrl' => $this->customUrl,
            'remoteKey' => $request->remoteKey,
        ]);

        return new RemoteFileRecord($objectUrl);
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

        if (function_exists('mime_content_type')) {
            $contentType = mime_content_type($filePath);
        }

        return $contentType ?: 'application/octet-stream';
    }
}
