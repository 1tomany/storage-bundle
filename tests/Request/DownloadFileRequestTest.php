<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Exception\InvalidArgumentException;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function sys_get_temp_dir;

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

    public function testSettingEmptyDirectoryForcesDirectoryToBeSystemTempDirectory(): void
    {
        $this->assertEquals(sys_get_temp_dir(), new DownloadFileRequest('file.jpeg', null)->getDirectory());
    }

    public function testSettingNonDirectoryForcesDirectoryToBeSystemTempDirectory(): void
    {
        $this->assertEquals(sys_get_temp_dir(), new DownloadFileRequest('file.jpeg', __FILE__)->getDirectory());
    }

    public function testSettingNonWritableDirectoryForcesDirectoryToBeSystemTempDirectory(): void
    {
        $this->assertEquals(sys_get_temp_dir(), new DownloadFileRequest('file.jpeg', '/')->getDirectory());
    }
}
