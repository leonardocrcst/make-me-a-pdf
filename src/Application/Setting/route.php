<?php

use App\Application\UseCase\InformeRendimentos;
use App\Application\UseCase\SanityCheck;
use Slim\App;

return function (App $app): void
{
    $app->put('/api/generate/sanity-check', SanityCheck::class);
    $app->put('/api/generate/informe-rendimentos', InformeRendimentos::class);
};
