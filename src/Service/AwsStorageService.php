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

    public function __construct(
        // @phpstan-ignore-next-line
        private S3Client $s3Client,
        private string $bucket,
        private ?string $customUrl,
    ) {
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
                'Key' => $request->key,
            ]);

            $body = $file->get('Body'); // @phpstan-ignore-line

            if (!$body instanceof StreamInterface) {
                throw new \RuntimeException('Failed to stream the file contents.');
            }

            // Attempt to Resolve Extension
            $extension = Path::getExtension(...[
                'path' => $request->key,
            ]);

            if (!empty($extension = trim($extension))) {
                $extension = sprintf('.%s', $extension);
            }

            $path = $filesystem->tempnam(sys_get_temp_dir(), DownloadFileRequest::PREFIX, $extension);
        } catch (\Exception $e) {
            throw new DownloadingFileFailedException($request->key, $e);
        }

        try {
            $filesystem->dumpFile($path, $body->getContents());
        } catch (\Exception $e) {
            if ($filesystem->exists($path)) {
                $filesystem->remove($path);
            }

            throw new DownloadingFileFailedException($request->key, $e);
        }

        return new LocalFileRecord($path);
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
