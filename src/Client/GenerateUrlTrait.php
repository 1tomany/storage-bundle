<?php

namespace OneToMany\StorageBundle\Client;

use function implode;
use function rtrim;
use function trim;

trait GenerateUrlTrait
{
    private function generateUrl(string $url, ?string $customUrl, string $remoteKey): string
    {
        // Ensure the custom URL is a slim as possible
        $customUrl = rtrim(trim($customUrl ?? ''), '/');

        if (!$customUrl) {
            return $url;
        }

        return implode('/', [$customUrl, $remoteKey]);
    }
}
