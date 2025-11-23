<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Request\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class UploadFileRequestTest extends TestCase
{
    protected function setUp(): void
    {
    }

    public function testCreatingPublicRequestSetsAclAsPublic(): void
    {
        $this->assertTrue(UploadFileRequest::public('php-logo.png', 'image/png', 'php-logo.png')->isPublic());
    }

    public function testCreatingPrivateRequestSetsAclAsPublic(): void
    {
        $this->assertFalse(UploadFileRequest::private('php-logo.png', 'image/png', 'php-logo.png')->isPublic());
    }
}
