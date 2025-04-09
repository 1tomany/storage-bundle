<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Request\UploadFileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
final class UploadFileRequestTest extends TestCase
{

    public function testGettingUrlUsesCanonicalUrlWhenCustomUrlIsEmpty(): void
    {
        $canonicalUrl = 'https://canonical-url.com/file.jpeg';

        $request = new UploadFileRequest(...[
            'filePath' => 'file.jpeg',
            'remoteKey' => 'file.jpeg',
            'customUrl' => null,
        ]);

        $this->assertEquals($canonicalUrl, $request->getUrl($canonicalUrl));
    }
}
