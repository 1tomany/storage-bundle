<?php

namespace OneToMany\StorageBundle\Tests\Request;

use OneToMany\StorageBundle\Request\UploadFileRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
final class UploadFileRequestTest extends TestCase
{
    public function testGettingUrlUsesCanonicalUrlWhenCustomUrlIsEmpty(): void
    {
        $remoteKey = 'file.jpeg';
        $canonicalUrl = 'https://canonical.example/'.$remoteKey;

        $request = new UploadFileRequest(...[
            'filePath' => $remoteKey,
            'remoteKey' => $remoteKey,
        ]);

        $this->assertEquals($canonicalUrl, $request->getUrl($canonicalUrl));
    }

    #[DataProvider('providerCustomUrlAndStrippedCustomUrl')]
    public function testGettingUrlStripsEndingSlashFromCustomUrl(
        string $customUrl,
        string $strippedCustomUrl,
    ): void {
        $remoteKey = 'file.jpeg';

        $objectUrl = $strippedCustomUrl.'/'.$remoteKey;
        $canonicalUrl = 'https://canonical.example/'.$remoteKey;

        $request = new UploadFileRequest(...[
            'filePath' => $remoteKey,
            'remoteKey' => $remoteKey,
            'customUrl' => $customUrl,
        ]);

        $this->assertNotEquals($objectUrl, $canonicalUrl);
        $this->assertEquals($objectUrl, $request->getUrl($canonicalUrl));
    }

    /**
     * @return list<list<non-empty-string>>
     */
    public static function providerCustomUrlAndStrippedCustomUrl(): array
    {
        $provider = [
            [
                'https://custom.example',
                'https://custom.example',
            ],
            [
                'https://custom.example/',
                'https://custom.example',
            ],
        ];

        return $provider;
    }
}
