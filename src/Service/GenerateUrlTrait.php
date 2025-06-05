<?php

namespace OneToMany\StorageBundle\Service;

use function rtrim;
use function trim;

trait GenerateUrlTrait
{
    private function generateUrl(string $url, ?string $customUrl, string $key): string
    {
        // Ensure the custom URL is a slim as possible
        $customUrl = rtrim(trim($customUrl ?? ''), '/');

        if (!$customUrl) {
            return $url;
        }

        return implode('/', [$customUrl, $key]);
    }
}
