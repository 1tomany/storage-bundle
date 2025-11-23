<?php

namespace OneToMany\StorageBundle\Client\Amazon;

use Aws\S3\S3Client;
use OneToMany\StorageBundle\Client\GenerateUrlTrait;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Request\UploadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Exception\UploadingFileFailedException;
use OneToMany\StorageBundle\Response\DownloadedFileResponse;
use OneToMany\StorageBundle\Response\UploadedFileResponse;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Exception\ExceptionInterface as FilesystemExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

use function class_exists;
use function file_exists;
use function is_readable;
use function is_writable;
use function sprintf;

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
        if (!is_writable($request->getDirectory())) {
            throw new InvalidArgumentException(sprintf('Downloading the file "%s" failed because the directory "%s" is not writable.', $request->getKey(), $request->getDirectory()));
        }

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
            throw new RuntimeException(sprintf('Downloading the file "%s" failed.', $request->getKey()), previous: $e);
        }

        try {
            // Resolve the extension for the temporary file
            $extension = Path::getExtension($request->getKey(), true);

            if (false === empty($extension)) {
                $extension = ".{$extension}";
            }

            $filePath = $this->filesystem->tempnam($request->getDirectory(), $request::PREFIX, $extension);
        } catch (FilesystemExceptionInterface $e) {
            throw new RuntimeException(sprintf('Downloading the file "%s" failed because a temporary file could not be created on the filesystem.', $request->getKey()), previous: $e);
        }

        try {
            $this->filesystem->dumpFile($filePath, $body->getContents());
        } catch (FilesystemExceptionInterface $e) {
            if ($this->filesystem->exists($filePath)) {
                $this->filesystem->remove($filePath);
            }

            throw new RuntimeException(sprintf('Downloading the file "%s" failed because the file contents could not be written to "%s".', $request->getKey(), $filePath), previous: $e);
        }

        return new DownloadedFileResponse($filePath);
    }

    public function upload(UploadFileRequestInterface $request): UploadedFileResponseInterface
    {
        if (!file_exists($request->getPath()) || !is_readable($request->getPath())) {
            throw new InvalidArgumentException(sprintf('Uploading the file "%s" failed because the file does not exist or is not readable.', $request->getPath()));
        }

        try {
            $acl = function (bool $isPublic): string {
                return $isPublic ? 'public-read' : 'private';
            };

            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $request->getKey(),
                'SourceFile' => $request->getPath(),
                'ACL' => $acl($request->isPublic()),
                'Content-Type' => $request->getMimeType(),
            ]);

            /** @var non-empty-string $url */
            $url = $result->get('ObjectURL'); // @phpstan-ignore-line
        } catch (\Exception $e) {
            throw new UploadingFileFailedException($request->key, $e);
        }

        return new UploadedFileResponse($this->generateUrl($url, $this->customUrl, $request->getKey()));
    }
}
