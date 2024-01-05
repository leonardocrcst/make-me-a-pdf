<?php

namespace App\Application\UseCase;

use App\Application\Response\JsonResponseInterface;
use Dompdf\Dompdf;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class UseCase
{
    protected ServerRequestInterface $request;
    protected ResponseInterface $response;
    protected array $requestContent = [];

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        $requestContent = $request->getBody()->getContents();
        if (!empty($requestContent)) {
            $this->requestContent = json_decode($requestContent, true);
        }
        $responseBody = $this->execute();
        $this->response->getBody()->write($responseBody->toJson());
        return $this->response
            ->withStatus($responseBody->statusCode)
            ->withHeader('Content-Type', 'Application/Json');
    }

    protected function createFromTemplate(string $templateFile): JsonResponse
    {
        $template = file_get_contents(__DIR__ . "/../Template/$templateFile");
        $filename = $this->getFilename();
        if (!file_exists(__DIR__ . '/../../../public/output/' . $filename)) {
            $html = $this->parseContent($template);
            $this->createPdf($html, $filename);
        }
        return new JsonResponse($this->getPath($filename));
    }

    protected function parseContent(string $template): string
    {
        $html = $template;
        foreach ($this->requestContent as $key => $value) {
            if (is_array($value)) {
                $value = implode('<br>', $value);
            }
            $html = str_replace("{{\$$key}}", $value, $html);
        }
        return $html;
    }

    protected function getFilename(): string
    {
        return md5(json_encode($this->requestContent)) . '.pdf';
    }

    protected function createPdf(string $html, string $filename): void
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $generated = $dompdf->output();
        file_put_contents(__DIR__.'/../../../public/output/' . $filename, $generated);
    }

    protected function getPath(string $filename): string
    {
        $host = $this->request->getUri()->getHost();
        return "$host/$filename";
    }

    abstract public function execute(): JsonResponseInterface;
}
