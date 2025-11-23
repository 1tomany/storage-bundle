<?php

namespace OneToMany\StorageBundle\Client\Amazon;

use Aws\S3\S3Client;
use OneToMany\StorageBundle\Client\GenerateUrlTrait;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Exception\LocalFileNotReadableForUploadException;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Exception\UploadingFileFailedException;
use OneToMany\StorageBundle\Record\LocalFileRecord;
use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Exception\ExceptionInterface as FilesystemExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

use function class_exists;
use function file_exists;
use function is_readable;
use function sprintf;
use function sys_get_temp_dir;

class S3StorageClient implements StorageClientInterface
{
    use GenerateUrlTrait;

    private Filesystem $filesystem;

    public function __construct(
        private S3Client $s3Client, // @phpstan-ignore-line
        private string $bucket,
        private ?string $customUrl,
    ) {
        if (!class_exists(S3Client::class)) {
            throw new RuntimeException('This storage service can not be used because the AWS SDK is not installed. Try running "composer require aws/aws-sdk-php-symfony".');
        }

        $this->filesystem = new Filesystem();
    }

    public function download(DownloadFileRequestInterface $request): DownloadedFileResponseInterface
    {
        try {
            $file = $this->s3Client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $request->getKey(),
            ]);

            $body = $file->get('Body'); // @phpstan-ignore-line

            if (!$body instanceof StreamInterface) {
                throw new RuntimeException(sprintf('Downloading the file "%s" failed because the remote server failed to stream the contents.', $request->getKey()));
            }
        } catch (\Exception $e) {
        }

        try {
            // Resolve the extension for the temporary file
            $extension = Path::getExtension($request->getKey(), true);

            if (!empty($extension)) {
                $extension = ".{$extension}";
            }

            $temporaryFilePath = $this->filesystem->tempnam(sys_get_temp_dir(), DownloadFileRequestInterface::PREFIX, $extension);
        } catch (FilesystemExceptionInterface $e) {
            throw new RuntimeException(sprintf('Downloading the file "%s" failed because a temporary file could not be created on the filesystem.', $request->getKey()), previous: $e);
        }

        try {
            $this->filesystem->dumpFile($temporaryFilePath, $body->getContents());
        } catch (FilesystemExceptionInterface $e) {
            if ($this->filesystem->exists($temporaryFilePath)) {
                $this->filesystem->remove($temporaryFilePath);
            }

            throw new RuntimeException(sprintf('Downloading the file "%s" failed because the file contents could not be written to "%s".', $request->getKey(), $temporaryFilePath), previous: $e);
        }

        return new LocalFileRecord($temporaryFilePath);
    }

    /**
     * @see OneToMany\StorageBundle\StorageServiceInterface
     */
    public function upload(UploadFileRequest $request): RemoteFileRecord
    {
        if (!file_exists($request->path) || !is_readable($request->path)) {
            throw new LocalFileNotReadableForUploadException($request->path);
        }

        try {
            $acl = function (bool $isPublic): string {
                return $isPublic ? 'public-read' : 'private';
            };

            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $request->key,
                'Content-Type' => $request->type,
                'SourceFile' => $request->path,
                'ACL' => $acl($request->isPublic),
            ]);

            /** @var non-empty-string $url */
            $url = $result->get('ObjectURL'); // @phpstan-ignore-line
        } catch (\Exception $e) {
            throw new UploadingFileFailedException($request->key, $e);
        }

        return new RemoteFileRecord($this->generateUrl($url, $this->customUrl, $request->key));
    }
}
