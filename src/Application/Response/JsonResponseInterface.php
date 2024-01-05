<?php

namespace App\Application\Response;

/**
 * @property int $statusCode
 */
interface JsonResponseInterface
{
    public function toJson(): string;
}
