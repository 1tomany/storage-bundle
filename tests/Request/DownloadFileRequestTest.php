<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Exception\InvalidArgumentException;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class DownloadFileRequestTest extends TestCase
{
    public function testConstructorRequiresNonEmptyKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The key cannot be empty.');

        new DownloadFileRequest('');
    }
}
