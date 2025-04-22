<?php

namespace OneToMany\StorageBundle\Tests\Service;

use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use OneToMany\StorageBundle\Service\MockStorageService;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function OneToMany\DataUri\parse_data;

#[Group('UnitTests')]
#[Group('ServiceTests')]
final class MockStorageServiceTest extends TestCase
{
    public function testDownloadingFileIsNotImplemented(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Not implemented!');

        new MockStorageService('mock-bucket')->download(...[
            'request' => new DownloadFileRequest('file.jpeg'),
        ]);
    }

    public function testUploadingFileWithoutCustomUrl(): void
    {
        $file = parse_data(__DIR__.'/../data/php-logo.png');

        $bucket = 'mock-bucket';
        $baseUrl = 'https://mock-storage.service';
        $fileUrl = $baseUrl.'/'.$bucket.'/'.$file->remoteKey;

        $record = new MockStorageService($bucket, null)->upload(...[
            'request' => UploadFileRequest::public($file),
        ]);

        $this->assertEquals($fileUrl, $record->url);
    }

    public function testUploadingFileWithCustomUrl(): void
    {
        $file = parse_data(__DIR__.'/../data/php-logo.png');

        $bucket = 'mock-bucket';
        $baseUrl = 'https://custom-cdn.com';
        $fileUrl = $baseUrl.'/'.$file->remoteKey;

        $record = new MockStorageService($bucket, $baseUrl)->upload(...[
            'request' => UploadFileRequest::public($file),
        ]);

        $this->assertEquals($fileUrl, $record->url);
    }
}
