<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Request\DownloadRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function sys_get_temp_dir;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class DownloadRequestTest extends TestCase
{
    public function testSettingEmptyDirectoryForcesDirectoryToBeSystemTempDirectory(): void
    {
        $this->assertEquals(sys_get_temp_dir(), new DownloadRequest('file.jpeg', null)->getDirectory());
    }

    public function testSettingNonDirectoryForcesDirectoryToBeSystemTempDirectory(): void
    {
        $this->assertEquals(sys_get_temp_dir(), new DownloadRequest('file.jpeg', __FILE__)->getDirectory());
    }

    public function testSettingNonWritableDirectoryForcesDirectoryToBeSystemTempDirectory(): void
    {
        $this->assertEquals(sys_get_temp_dir(), new DownloadRequest('file.jpeg', '/')->getDirectory());
    }
}
