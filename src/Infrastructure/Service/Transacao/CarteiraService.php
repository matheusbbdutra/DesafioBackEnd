<?php

namespace App\Infrastructure\Service\Transacao;

use App\Domain\Transacao\Entity\Carteira;
use App\Domain\Usuario\Entity\Usuario;
use App\Infrastructure\Repository\Transacao\CarteiraRepository;

class CarteiraService
{
    public function __construct(private CarteiraRepository $carteiraRepository)
    {
    }

    public function criarCarteira(Usuario $usuario): void
    {
        $carteira = new Carteira();
        $carteira->setUsuario($usuario);
        $this->carteiraRepository->criarCarteira($carteira);
    }
}
