<?php

namespace App\Application\UseCase;

use App\Application\Response\JsonResponseInterface;

class SanityCheck extends UseCase
{
    public function execute(): JsonResponseInterface
    {
        return $this->createFromTemplate('sanity-check.html');
    }
}
