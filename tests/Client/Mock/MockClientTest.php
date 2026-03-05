<?php

namespace OneToMany\StorageBundle\Tests\Client\Mock;

use OneToMany\StorageBundle\Client\Mock\MockClient;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Request\DownloadRequest;
use OneToMany\StorageBundle\Request\UploadRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ClientTests')]
final class MockClientTest extends TestCase
{
    public function testDownloadingFileIsNotImplemented(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not implemented!');

        new MockClient('mock-bucket')->download(...[
            'request' => new DownloadRequest('label.jpeg'),
        ]);
    }

    public function testUploadingFileWithoutCustomUrl(): void
    {
        $response = $this->createStorageClient()->upload(...[
            'request' => $this->createUploadRequest(),
        ]);

        $this->assertEquals('https://mock-storage.service/mock-bucket/php-logo.png', $response->getUrl());
    }

    public function testUploadingFileWithCustomUrl(): void
    {
        $response = $this->createStorageClient(customUrl: 'https://custom-cdn.com')->upload(...[
            'request' => $this->createUploadRequest(),
        ]);

        $this->assertEquals('https://custom-cdn.com/php-logo.png', $response->getUrl());
    }

    private function createStorageClient(string $bucket = 'mock-bucket', ?string $customUrl = null): MockClient
    {
        return new MockClient($bucket, $customUrl);
    }

    private function createUploadRequest(): UploadRequest
    {
        return new UploadRequest(__DIR__.'/../../.data/label.jpeg', 'image/jpeg', 'label.jpeg');
    }
}
