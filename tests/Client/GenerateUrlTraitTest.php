<?php

namespace OneToMany\StorageBundle\Tests\Client;

use OneToMany\StorageBundle\Client\GenerateUrlTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ClientTests')]
final class GenerateUrlTraitTest extends TestCase
{
    use GenerateUrlTrait;

    #[DataProvider('providerKeyCustomUrlAndCanonicalUrl')]
    public function testGeneratingUrlUsesCanonicalUrlWhenCustomUrlIsEmpty(string $key, ?string $customUrl, string $canonicalUrl): void
    {
        $this->assertEquals($canonicalUrl, $this->generateUrl($canonicalUrl, $customUrl, $key));
    }

    public static function providerKeyCustomUrlAndCanonicalUrl(): array
    {
        $provider = [
            ['file.jpeg', '', 'https://canonical-url.example/file.jpeg'],
            ['file.jpeg', null, 'https://canonical-url.example/file.jpeg'],
            ['ab/file.jpeg', null, 'https://canonical-url.example/ab/file.jpeg'],
            ['ab/12/file.jpeg', null, 'https://canonical-url.example/ab/12/file.jpeg'],
        ];

        return $provider;
    }

    #[DataProvider('providerKeyCustomUrlAndGeneratedUrl')]
    public function testGeneratingUrlStripsEndingSlashesFromCustomUrl(string $key, string $customUrl, string $generatedUrl): void
    {
        $this->assertEquals($generatedUrl, $this->generateUrl('', $customUrl, $key));
    }

    /**
     * @return list<list<non-empty-string>>
     */
    public static function providerKeyCustomUrlAndGeneratedUrl(): array
    {
        $provider = [
            ['file.jpeg', 'https://custom-url.example', 'https://custom-url.example/file.jpeg'],
            ['file.jpeg', 'https://custom-url.example/', 'https://custom-url.example/file.jpeg'],
            ['file.jpeg', 'https://custom-url.example//', 'https://custom-url.example/file.jpeg'],
            ['ab/12/file.jpeg', 'https://custom-url.example', 'https://custom-url.example/ab/12/file.jpeg'],
            ['ab/12/file.jpeg', 'https://custom-url.example/', 'https://custom-url.example/ab/12/file.jpeg'],
        ];

        return $provider;
    }
}
