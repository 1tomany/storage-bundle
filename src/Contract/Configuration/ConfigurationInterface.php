<?php

namespace OneToMany\StorageBundle\Contract\Configuration;

interface ConfigurationInterface
{
    public function getVersion(): string;

    // public function setVersion(string $version): ConfigurationInterface;

    public function getRegion(): string;

    // public function setRegion(string $region): ConfigurationInterface;

    public function getEndpoint(): ?string;

    // public function setEndpoint(?string $endpoint): ConfigurationInterface;

    public function getKey(): string;

    // public function setKey(string $key): ConfigurationInterface;

    public function getSecret(): string;

    // public function setSecret(string $secret): ConfigurationInterface;
}
