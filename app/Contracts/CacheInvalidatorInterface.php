<?php

namespace App\Contracts;

interface CacheInvalidatorInterface
{
    public function invalidateCache();
}
