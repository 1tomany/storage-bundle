<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Request\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Path;

use function mime_content_type;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class UploadFileRequestTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        $this->path = Path::canonicalize(__DIR__.'/../data/php-logo.png');
    }

    public function testCreatingPublicRequestSetsAclAsPublic(): void
    {
        $this->assertTrue(UploadFileRequest::public($this->path, (string) mime_content_type($this->path), $this->path)->isPublic());
    }

    public function testCreatingPrivateRequestSetsAclAsPublic(): void
    {
        $this->assertFalse(UploadFileRequest::private($this->path, (string) mime_content_type($this->path), $this->path)->isPublic());
    }
}
