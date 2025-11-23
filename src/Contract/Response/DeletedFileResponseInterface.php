<?php

namespace OneToMany\StorageBundle\Contract\Response;

interface DeletedFileResponseInterface
{
    /**
     * @return non-empty-string
     */
    public function getKey(): string;
}
