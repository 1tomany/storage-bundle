<?php

namespace OneToMany\StorageBundle\Tests\Service;

use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use OneToMany\StorageBundle\Service\MockStorageService;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

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
        $bucket = 'mock-bucket';
        $remoteKey = 'file.jpeg';

        $serviceUrl = 'https://mock-storage.service';
        $objectUrl = $serviceUrl.'/'.$bucket.'/'.$remoteKey;

        $record = new MockStorageService($bucket, null)->upload(...[
            'request' => UploadFileRequest::public($remoteKey, $remoteKey),
        ]);

        $this->assertEquals($objectUrl, $record->url);
    }

    public function testUploadingFileWithCustomUrl(): void
    {
        $bucket = 'mock-bucket';
        $remoteKey = 'file.jpeg';

        $customUrl = 'https://custom-cdn.com';
        $objectUrl = $customUrl.'/'.$remoteKey;

        $record = new MockStorageService($bucket, $customUrl)->upload(...[
            'request' => UploadFileRequest::public($remoteKey, $remoteKey),
        ]);

        $this->assertEquals($objectUrl, $record->url);
    }
}
