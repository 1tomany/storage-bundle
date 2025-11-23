<?php

namespace OneToMany\StorageBundle\Contract\Request;

interface UploadFileRequestInterface
{
    /**
     * @return non-empty-string
     */
    public function getPath(): string;

    public function setPath(string $path): static;

    /**
     * @return non-empty-string
     */
    public function getMimeType(): string;

    public function setMimeType(string $mimeType): static;

    /**
     * @return non-empty-string
     */
    public function getKey(): string;

    public function setKey(string $key): static;

    public function isPublic(): bool;

    public function markAsPublic(): static;

    public function markAsPrivate(): static;
}
