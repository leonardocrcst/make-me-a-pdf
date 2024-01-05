<?php

namespace App\Application\UseCase;

use App\Application\Response\JsonResponseInterface;

class BuiltInTemplate extends UseCase
{
    public function execute(): JsonResponseInterface
    {
        $html = $this->parseContent($this->requestContent['template']);
        $filename = $this->getFilename();
        $this->createPdf($html, $filename);
        return new JsonResponse($this->getPath($filename));
    }
}
