<?php

namespace OneToMany\StorageBundle\Client\Trait;

use function implode;
use function rtrim;
use function trim;

trait GenerateUrlTrait
{
    protected function generateUrl(string $url, ?string $customUrl, string $key): string
    {
        // Ensure the custom URL is a slim as possible
        $customUrl = rtrim(trim($customUrl ?? ''), '/');

        if (!$customUrl) {
            return $url;
        }

        return implode('/', [$customUrl, $key]);
    }
}
