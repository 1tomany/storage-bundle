<?php

namespace OneToMany\StorageBundle\Client\Amazon;

use OneToMany\StorageBundle\Client\BaseClient;
use OneToMany\StorageBundle\Contract\Configuration\ConfigurationInterface;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Request\DeleteRequest;
use OneToMany\StorageBundle\Request\DownloadRequest;
use OneToMany\StorageBundle\Request\UploadRequest;
use OneToMany\StorageBundle\Response\DeleteResponse;
use OneToMany\StorageBundle\Response\DownloadResponse;
use OneToMany\StorageBundle\Response\UploadResponse;
use Symfony\Component\Filesystem\Exception\ExceptionInterface as FilesystemExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function array_keys;
use function file_get_contents;
use function hash;
use function hash_hmac;
use function implode;
use function is_string;
use function ksort;
use function parse_url;
use function rtrim;
use function sprintf;
use function strtolower;
use function trim;

class AmazonClient extends BaseClient
{
    private const string ALGORITHM = 'AWS4-HMAC-SHA256';
    private const string SERVICE = 's3';
    private const string EMPTY_PAYLOAD_HASH = 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855';

    public function __construct(
        private ConfigurationInterface $configuration,
        private HttpClientInterface $httpClient,
        string $bucket,
        ?string $customUrl,
    ) {
        parent::__construct($bucket, $customUrl);
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\ClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        try {
            $body = file_get_contents($request->getPath());

            if (false === $body) {
                throw new RuntimeException(sprintf('Failed to read the file "%s".', $request->getPath()));
            }

            $payloadHash = hash('sha256', $body);
            $key = $request->getKey();
            $uri = '/'.$key;
            $endpoint = $this->resolveEndpoint();
            $host = $this->resolveHost($endpoint);
            $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

            $headers = [
                'Host' => $host,
                'Content-Type' => $request->getFormat(),
                'x-amz-acl' => $request->isPublic() ? 'public-read' : 'private',
                'x-amz-content-sha256' => $payloadHash,
                'x-amz-date' => $now->format('Ymd\THis\Z'),
            ];

            $headers['Authorization'] = $this->signRequest('PUT', $uri, '', $headers, $payloadHash, $now);

            $response = $this->httpClient->request('PUT', $endpoint.$uri, [
                'headers' => $headers,
                'body' => $body,
            ]);

            if ($response->getStatusCode() >= 300) {
                throw new RuntimeException(sprintf('Uploading the file "%s" to "%s" failed with status code %d.', $request->getPath(), $key, $response->getStatusCode()));
            }

            $url = $endpoint.$uri;
        } catch (RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf('Uploading the file "%s" to "%s" failed.', $request->getPath(), $request->getKey()), previous: $e);
        }

        return new UploadResponse($this->generateUrl($url, $this->getCustomUrl(), $request->getKey()));
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Client\ClientInterface
     */
    public function download(DownloadRequest $request): DownloadResponse
    {
        $filesystem = new Filesystem();

        try {
            $key = $request->getKey();
            $uri = '/'.$key;
            $endpoint = $this->resolveEndpoint();
            $host = $this->resolveHost($endpoint);
            $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

            $headers = [
                'Host' => $host,
                'x-amz-content-sha256' => self::EMPTY_PAYLOAD_HASH,
                'x-amz-date' => $now->format('Ymd\THis\Z'),
            ];

            $headers['Authorization'] = $this->signRequest('GET', $uri, '', $headers, self::EMPTY_PAYLOAD_HASH, $now);

            $response = $this->httpClient->request('GET', $endpoint.$uri, [
                'headers' => $headers,
            ]);

            if ($response->getStatusCode() >= 300) {
                throw new RuntimeException(sprintf('Downloading the file "%s" failed with status code %d.', $key, $response->getStatusCode()));
            }

            $body = $response->getContent();
        } catch (RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf('Downloading the file "%s" failed.', $request->getKey()), previous: $e);
        }

        // Resolve the extension for the temporary file
        $ext = Path::getExtension($request->getKey(), true);

        try {
            $path = $filesystem->tempnam($request->getDirectory(), $request::FILE_PREFIX, $ext ?: ".{$ext}");
        } catch (FilesystemExceptionInterface $e) {
            throw new RuntimeException(sprintf('Downloading the file "%s" failed because a temporary file could not be created.', $request->getKey()), previous: $e);
        }

        try {
            $filesystem->dumpFile($path, $body);
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
            $key = $request->getKey();
            $uri = '/'.$key;
            $endpoint = $this->resolveEndpoint();
            $host = $this->resolveHost($endpoint);
            $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

            $headers = [
                'Host' => $host,
                'x-amz-content-sha256' => self::EMPTY_PAYLOAD_HASH,
                'x-amz-date' => $now->format('Ymd\THis\Z'),
            ];

            $headers['Authorization'] = $this->signRequest('DELETE', $uri, '', $headers, self::EMPTY_PAYLOAD_HASH, $now);

            $response = $this->httpClient->request('DELETE', $endpoint.$uri, [
                'headers' => $headers,
            ]);

            if ($response->getStatusCode() >= 300) {
                throw new RuntimeException(sprintf('Deleting the file "%s" failed with status code %d.', $key, $response->getStatusCode()));
            }
        } catch (RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf('Deleting the file "%s" failed.', $request->getKey()), previous: $e);
        }

        return new DeleteResponse($request->getKey());
    }

    /**
     * @return non-empty-string
     */
    private function resolveEndpoint(): string
    {
        $endpoint = $this->configuration->getEndpoint();

        if (is_string($endpoint) && '' !== $endpoint) {
            return rtrim($endpoint, '/').'/'.$this->getBucket();
        }

        $region = $this->configuration->getRegion();

        return sprintf('https://%s.s3.%s.amazonaws.com', $this->getBucket(), $region);
    }

    /**
     * @return non-empty-string
     */
    private function resolveHost(string $endpoint): string
    {
        $parsed = parse_url($endpoint, PHP_URL_HOST);

        if (!is_string($parsed) || '' === $parsed) {
            throw new RuntimeException(sprintf('Failed to resolve the host from the endpoint "%s".', $endpoint));
        }

        return $parsed;
    }

    /**
     * @param array<string, string> $headers
     */
    private function signRequest(string $method, string $uri, string $queryString, array $headers, string $payloadHash, \DateTimeImmutable $now): string
    {
        $date = $now->format('Ymd');
        $timestamp = $now->format('Ymd\THis\Z');
        $region = $this->configuration->getRegion();
        $scope = sprintf('%s/%s/%s/aws4_request', $date, $region, self::SERVICE);

        // Build canonical headers and signed headers
        $canonicalHeaders = [];
        $signedHeaderNames = [];

        foreach ($headers as $name => $value) {
            $lowered = strtolower($name);
            $canonicalHeaders[$lowered] = $lowered.':'.trim($value);
            $signedHeaderNames[$lowered] = true;
        }

        ksort($canonicalHeaders);
        ksort($signedHeaderNames);

        $canonicalHeadersString = implode("\n", $canonicalHeaders)."\n";
        $signedHeaders = implode(';', array_keys($signedHeaderNames));

        // Build canonical request
        $canonicalRequest = implode("\n", [
            $method,
            $uri,
            $queryString,
            $canonicalHeadersString,
            $signedHeaders,
            $payloadHash,
        ]);

        // Build string to sign
        $stringToSign = implode("\n", [
            self::ALGORITHM,
            $timestamp,
            $scope,
            hash('sha256', $canonicalRequest),
        ]);

        // Derive signing key
        $signingKey = $this->deriveSigningKey($date, $region);

        // Calculate signature
        $signature = hash_hmac('sha256', $stringToSign, $signingKey);

        return sprintf(
            '%s Credential=%s/%s,SignedHeaders=%s,Signature=%s',
            self::ALGORITHM,
            $this->configuration->getKey(),
            $scope,
            $signedHeaders,
            $signature,
        );
    }

    private function deriveSigningKey(string $date, string $region): string
    {
        $dateKey = hash_hmac('sha256', $date, 'AWS4'.$this->configuration->getSecret(), true);
        $regionKey = hash_hmac('sha256', $region, $dateKey, true);
        $serviceKey = hash_hmac('sha256', self::SERVICE, $regionKey, true);

        return hash_hmac('sha256', 'aws4_request', $serviceKey, true);
    }
}
