<?php

namespace OneToMany\StorageBundle\Configuration;

use OneToMany\StorageBundle\Contract\Configuration\ConfigurationInterface;

readonly class Configuration implements ConfigurationInterface
{
    /**
     * @param non-empty-string $version
     * @param non-empty-string $region
     * @param ?non-empty-string $endpoint
     * @param non-empty-string $key
     * @param non-empty-string $secret
     */
    public function __construct(
        private string $version = 'latest',
        private string $region = 'auto',
        private string $key = '',
        private string $secret = '',
        private ?string $endpoint = null,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return non-empty-string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return ?non-empty-string
     */
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @return non-empty-string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return non-empty-string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }
}
