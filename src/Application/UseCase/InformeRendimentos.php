<?php

namespace App\Application\UseCase;

use App\Application\Response\JsonResponseInterface;

class InformeRendimentos extends UseCase
{
    public function execute(): JsonResponseInterface
    {
        return $this->createFromTemplate('InformeRendimentosPessoaFisica.html');
    }
}
