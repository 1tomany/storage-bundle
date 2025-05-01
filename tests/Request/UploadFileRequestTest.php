<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Request\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class UploadFileRequestTest extends TestCase
{
    private string $path;
    private string $type;

    protected function setUp(): void
    {
        $this->path = __DIR__.'/../data/php-logo.png';

        // @phpstan-ignore-next-line
        $this->type = \mime_content_type($this->path);
    }

    public function testCreatingPublicRequestSetsAclAsPublic(): void
    {
        $this->assertTrue(UploadFileRequest::public($this->path, $this->type, $this->path)->isPublic);
    }

    public function testCreatingPrivateRequestSetsAclAsPublic(): void
    {
        $this->assertFalse(UploadFileRequest::private($this->path, $this->type, $this->path)->isPublic);
    }
}
