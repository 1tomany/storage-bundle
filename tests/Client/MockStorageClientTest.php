<?php

namespace OneToMany\StorageBundle\Tests\Client;

use OneToMany\StorageBundle\Client\Mock\MockStorageClient;
use OneToMany\StorageBundle\Exception\RuntimeException;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use OneToMany\StorageBundle\Tests\FileTestCase;
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
        $record = new MockStorageClient('mock-bucket', null)->upload(
            new UploadFileRequest('php-logo.png', 'image/png', 'php-logo.png'),
        );

        $this->assertEquals('https://mock-storage.service/mock-bucket/php-logo.png', $record->getUrl());
    }

    public function testUploadingFileWithCustomUrl(): void
    {
        $bucket = 'mock-bucket';
        $baseUrl = 'https://custom-cdn.com';
        $fileUrl = $baseUrl.'/'.$this->key;

        $record = new MockStorageClient($bucket, $baseUrl)->upload(new UploadFileRequest($this->path, $this->type, $this->key));

        $this->assertEquals($fileUrl, $record->url);
    }

    private function createMockStorageClient(string $bucket, ?string $baseUrl = null): MockStorageClient
    {
        return new MockStorageClient($bucket, $baseUrl);
    }
}
