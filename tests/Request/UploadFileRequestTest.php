<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\DataUri\SmartFile;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function OneToMany\DataUri\parse_data;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class UploadFileRequestTest extends TestCase
{
    private SmartFile $file;

    protected function setUp(): void
    {
        $this->file = parse_data(__DIR__.'/../data/php-logo.png');
    }

    protected function tearDown(): void
    {
        unset($this->file);
    }

    public function testCreatingPublicRequestSetsAclAsPublic(): void
    {
        $this->assertTrue(UploadFileRequest::public($this->file)->isPublic);
    }

    public function testCreatingPrivateRequestSetsAclAsPublic(): void
    {
        $this->assertFalse(UploadFileRequest::private($this->file)->isPublic);
    }
}
