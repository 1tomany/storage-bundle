<?php

namespace OneToMany\StorageBundle\Service;

use function rtrim;
use function trim;

trait GenerateUrlTrait
{
    private function generateUrl(
        string $canonicalUrl,
        ?string $customUrl,
        string $remoteKey,
    ): string {
        // Ensure the custom URL is a slim as possible
        $customUrl = rtrim(trim($customUrl ?? ''), '/');

        if (empty($customUrl)) {
            return $canonicalUrl;
        }

        return implode('/', [$customUrl, $remoteKey]);
    }
}
