<?php

namespace App\Application\UseCase;

use App\Application\Response\JsonResponseInterface;

readonly class JsonResponse implements JsonResponseInterface
{
    public function __construct(
        public ?string $link = null,
        public ?int $statusCode = 200,
    ) {
    }

    public function toJson(): string
    {
        $response = [
            'statusCode' => $this->statusCode,
            'link' => $this->link
        ];
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }
}
