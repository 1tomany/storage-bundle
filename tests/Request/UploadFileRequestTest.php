<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Request\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class UploadFileRequestTest extends TestCase
{
    public function testCreatingPublicRequestSetsAclAsPublic(): void
    {
        $this->assertTrue(UploadFileRequest::public('', '')->isPublic);
    }

    public function testCreatingPrivateRequestSetsAclAsPublic(): void
    {
        $this->assertFalse(UploadFileRequest::private('', '')->isPublic);
    }
}
