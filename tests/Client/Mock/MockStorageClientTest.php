<?php

namespace OneToMany\StorageBundle\Tests\Client\Mock;

use OneToMany\StorageBundle\Client\Mock\MockStorageClient;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ClientTests')]
final class MockStorageClientTest extends TestCase
{
    public function testDownloadingFileIsNotImplemented(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not implemented!');

        new MockStorageClient('mock-bucket')->download(new DownloadFileRequest('file.jpeg'));
    }

    public function testUploadingFileWithoutCustomUrl(): void
    {
        $uploadFileRequest = new UploadFileRequest('php-logo.png', 'image/png', 'php-logo.png');

        $record = $this->createStorageClient()->upload(...[
            'request' => $uploadFileRequest,
        ]);

        $this->assertEquals('https://mock-storage.service/mock-bucket/php-logo.png', $record->getUrl());
    }

    public function testUploadingFileWithCustomUrl(): void
    {
        $uploadFileRequest = new UploadFileRequest('php-logo.png', 'image/png', 'php-logo.png');

        $record = $this->createStorageClient(customUrl: 'https://custom-cdn.com')->upload(...[
            'request' => $uploadFileRequest,
        ]);

        $this->assertEquals('https://custom-cdn.com/php-logo.png', $record->getUrl());
    }

    private function createStorageClient(string $bucket = 'mock-bucket', ?string $customUrl = null): MockStorageClient
    {
        return new MockStorageClient($bucket, $customUrl);
    }
}
