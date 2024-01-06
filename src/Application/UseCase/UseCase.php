<?php

namespace App\Application\UseCase;

use App\Application\Response\JsonResponseInterface;
use App\Model\HtmlToPdf;
use App\Model\Type\Orientation;
use App\Model\Type\Paper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class UseCase
{
    public const string OUTPUT_FOLDER = __DIR__ . '/../../../public/output/';
    public const string TEMPLATE_FOLDER = __DIR__ . '/../Template/';

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
        $filename = $this->getFilename();
        if (!file_exists(self::OUTPUT_FOLDER . $filename)) {
            $template = file_get_contents(self::TEMPLATE_FOLDER . $templateFile);
            $this->createPdf($template, $filename);
        }
        return new JsonResponse($this->getPath($filename));
    }

    protected function getFilename(): string
    {
        return md5(json_encode($this->requestContent)) . '.pdf';
    }

    protected function createPdf(string $html, string $filename): void
    {
        $generated = (new HtmlToPdf($html, $this->requestContent))
            ->create(Paper::A4, Orientation::PORTRAIT);
        file_put_contents(self::OUTPUT_FOLDER . $filename, $generated);
    }

    protected function getPath(string $filename): string
    {
        $host = $this->request->getUri()->getHost();
        return "$host/$filename";
    }

    abstract public function execute(): JsonResponseInterface;
}
