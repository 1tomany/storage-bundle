<?php

namespace OneToMany\StorageBundle\Tests\Service;

use OneToMany\StorageBundle\Service\GenerateUrlTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function rtrim;

#[Group('UnitTests')]
#[Group('ServiceTests')]
final class GenerateUrlTraitTest extends TestCase
{
    use GenerateUrlTrait;

    public function testGeneratingUrlUsesCanonicalUrlWhenCustomUrlIsEmpty(): void
    {
        $remoteKey = 'file.jpeg';
        $canonicalUrl = 'https://canonical-url.example/'.$remoteKey;

        $this->assertEquals($canonicalUrl, $this->generateUrl($canonicalUrl, null, $remoteKey));
    }

    #[DataProvider('providerCustomUrl')]
    public function testGeneratingUrlStripsEndingSlashesFromCustomUrl(string $customUrl): void
    {
        $remoteKey = 'file.jpeg';

        $objectUrl = rtrim($customUrl, '/');
        $objectUrl = $objectUrl.'/'.$remoteKey;

        $this->assertEquals($objectUrl, $this->generateUrl('', $customUrl, $remoteKey));
    }

    /**
     * @return list<list<non-empty-string>>
     */
    public static function providerCustomUrl(): array
    {
        $provider = [
            ['https://custom-url.example'],
            ['https://custom-url.example/'],
            ['https://custom-url.example//'],
            ['https://custom-url.example///'],
        ];

        return $provider;
    }
}
