<?php

namespace App\Infrastructure\Service\Usuario;

use App\Application\DTO\Usuario\UsuarioDTO;
use App\Domain\Usuario\Entity\Usuario;
use App\Domain\Usuario\ValueObject\Documento;
use App\Domain\Usuario\ValueObject\Email;
use App\Infrastructure\Repository\Usuario\UsuarioRepository;
use App\Infrastructure\Service\Transacao\CarteiraService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioService
{
    public function __construct(
        private UserPasswordHasherInterface $encoder,
        private UsuarioRepository $usuarioRepository,
        private CarteiraService $carteiraService,
    ) {
    }

    public function criarUsuario(UsuarioDTO $usuarioDTO): Usuario
    {
        $usuario = new Usuario();
        $usuario->setNome($usuarioDTO->nome);
        $usuario->setEmail(new Email($usuarioDTO->email));
        $usuario->setCpfCnpj(new Documento($usuarioDTO->cpfCnpj));
        $senha = $this->encoder->hashPassword($usuario, $usuarioDTO->senha);
        $usuario->setSenha($senha);
        $usuario->setIsLogista($usuarioDTO->isLogista ?: false);
        $this->usuarioRepository->persisteUsuario($usuario);
        $this->carteiraService->criarCarteira($usuario);

        return $usuario;
    }

    public function atualizarUsuario(UsuarioDTO $usuarioDTO): Usuario
    {
        /** @var Usuario $usuario */
        $usuario = $this->usuarioRepository->find($usuarioDTO->id);
        $usuario->setNome($usuarioDTO->nome);
        $usuario->setEmail(new Email($usuarioDTO->email));
        $usuario->setCpfCnpj(new Documento($usuarioDTO->cpfCnpj));
        $senha = $this->encoder->hashPassword($usuario, $usuarioDTO->senha);
        $usuario->setSenha($senha);
        $usuario->setIsLogista($usuarioDTO->isLogista ?: false);

        $this->usuarioRepository->persisteUsuario($usuario);

        return $usuario;
    }
}
