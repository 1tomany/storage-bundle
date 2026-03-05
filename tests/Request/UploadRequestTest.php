<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Request\UploadRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class UploadRequestTest extends TestCase
{
    public function testCreatingPublicRequestSetsAclAsPublic(): void
    {
        $this->assertTrue(UploadRequest::public(__DIR__.'/../.data/label.jpeg', 'image/jpeg', 'label.jpeg')->isPublic());
    }

    public function testCreatingPrivateRequestSetsAclAsPublic(): void
    {
        $this->assertFalse(UploadRequest::private(__DIR__.'/../.data/label.jpeg', 'image/jpeg', 'label.jpeg')->isPublic());
    }

    public function testMarkingAsPublic(): void
    {
        $this->assertTrue(new UploadRequest(__DIR__.'/../.data/label.jpeg', 'image/jpeg', 'label.jpeg')->markAsPublic()->isPublic());
    }

    public function testMarkingAsPrivate(): void
    {
        $this->assertFalse(new UploadRequest(__DIR__.'/../.data/label.jpeg', 'image/jpeg', 'label.jpeg')->markAsPrivate()->isPublic());
    }
}
