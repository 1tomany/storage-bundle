<?php

namespace OneToMany\StorageBundle\Service;

trait GenerateUrlTrait
{
    private function generateUrl(
        string $canonicalUrl,
        ?string $customUrl,
        string $remoteKey,
    ): string {
        $customUrl = rtrim((string) $customUrl, '/');

        if (empty($customUrl)) {
            return $canonicalUrl;
        }

        return implode('/', [$customUrl, $remoteKey]);
    }
}
