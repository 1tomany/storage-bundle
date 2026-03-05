<?php

namespace OneToMany\StorageBundle\Contract\Action;

use OneToMany\StorageBundle\Request\DeleteRequest;
use OneToMany\StorageBundle\Response\DeleteResponse;

interface DeleteActionInterface
{
    public function act(DeleteRequest $request): DeleteResponse;
}
