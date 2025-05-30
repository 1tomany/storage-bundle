<?php

namespace OneToMany\StorageBundle\Service;

use Aws\S3\S3Client;
use OneToMany\StorageBundle\Exception\DownloadingFileFailedException;
use OneToMany\StorageBundle\Exception\LocalFileNotReadableForUploadException;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Exception\UploadingFileFailedException;
use OneToMany\StorageBundle\Record\LocalFileRecord;
use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

use function class_exists;
use function file_exists;
use function is_readable;
use function sprintf;
use function sys_get_temp_dir;
use function trim;

final readonly class AwsStorageService implements StorageServiceInterface
{
    use GenerateUrlTrait;

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
            throw new RuntimeException('This storage service can not be used because the AWS SDK is not installed. Try running "composer require aws/aws-sdk-php-symfony".');
        }
    }

    /**
     * @see OneToMany\StorageBundle\StorageServiceInterface
     */
    public function download(DownloadFileRequest $request): LocalFileRecord
    {
        $filesystem = new Filesystem();

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

            $filePath = $filesystem->tempnam(sys_get_temp_dir(), DownloadFileRequest::PREFIX, $extension);
        } catch (\Exception $e) {
            throw new DownloadingFileFailedException($request->remoteKey, $e);
        }

        try {
            $filesystem->dumpFile($filePath, $body->getContents());
        } catch (\Exception $e) {
            if ($filesystem->exists($filePath)) {
                $filesystem->remove($filePath);
            }

            throw new DownloadingFileFailedException($request->remoteKey, $e);
        }

        return new LocalFileRecord($filePath);
    }

    /**
     * @see OneToMany\StorageBundle\StorageServiceInterface
     */
    public function upload(UploadFileRequest $request): RemoteFileRecord
    {
        if (!file_exists($request->filePath) || !is_readable($request->filePath)) {
            throw new LocalFileNotReadableForUploadException($request->filePath);
        }

        try {
            $acl = function (bool $isPublic): string {
                return $isPublic ? 'public-read' : 'private';
            };

            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $request->remoteKey,
                'ACL' => $acl($request->isPublic),
                'Content-Type' => $request->contentType,
                'SourceFile' => $request->filePath,
            ]);

            /** @var non-empty-string $canonicalUrl */
            $canonicalUrl = $result->get('ObjectURL'); // @phpstan-ignore-line
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
}
