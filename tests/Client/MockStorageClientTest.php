<?php

namespace OneToMany\StorageBundle\Tests\Client;

use OneToMany\StorageBundle\Client\Mock\MockStorageClient;
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
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Not implemented!');

        new MockStorageClient('mock-bucket')->download(new DownloadFileRequest('file.jpeg'));
    }

    /*
    public function testUploadingFileWithoutCustomUrl(): void
    {
        $bucket = 'mock-bucket';
        $fileUrl = 'https://mock-storage.service/'.$bucket.'/'.$this->key;

        $record = $this->createStorageService($bucket, null)->upload(
            new UploadFileRequest($this->path, $this->type, $this->key)
        );

        $this->assertEquals($fileUrl, $record->url);
    }

    public function testUploadingFileWithCustomUrl(): void
    {
        $bucket = 'mock-bucket';
        $baseUrl = 'https://custom-cdn.com';
        $fileUrl = $baseUrl.'/'.$this->key;

        $record = $this->createStorageService($bucket, $baseUrl)->upload(
            new UploadFileRequest($this->path, $this->type, $this->key)
        );

        $this->assertEquals($fileUrl, $record->url);
    }

    private function createStorageService(
        string $bucket,
        ?string $baseUrl = null,
    ): MockStorageService {
        return new MockStorageService($bucket, $baseUrl);
    }
    */
}
