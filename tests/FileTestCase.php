<?php

namespace OneToMany\StorageBundle\Tests;

use PHPUnit\Framework\TestCase;

use function basename;
use function mime_content_type;

abstract class FileTestCase extends TestCase
{
    protected string $path;
    protected string $type;
    protected string $key;

    protected function setUp(): void
    {
        // $this->key = 'php-logo.png';
        $this->path = __DIR__.'/data/php-logo.png';

        // @phpstan-ignore-next-line
        $this->type = mime_content_type($this->path);
        $this->key = basename($this->path);
    }
}
