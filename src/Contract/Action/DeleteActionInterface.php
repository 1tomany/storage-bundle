<?php

namespace OneToMany\StorageBundle\Contract\Action;

use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;
use OneToMany\StorageBundle\Request\DeleteRequest;

interface DeleteActionInterface
{
    public function act(DeleteRequest $request): DeletedFileResponseInterface;
}
