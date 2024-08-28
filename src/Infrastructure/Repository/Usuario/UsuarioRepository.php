<?php

namespace App\Infrastructure\Repository\Usuario;

use App\Domain\Usuario\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Usuario>
 */
class UsuarioRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    public function persisteUsuario(Usuario $usuario): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();

        try {
            $entityManager->persist($usuario);
            $entityManager->flush();
            $entityManager->commit();
        } catch (UniqueConstraintViolationException $e) {
            $entityManager->rollback();

            throw new \RuntimeException('Erro: Já existe um usuário cadastrado para esse CPF.', 0, $e);
        } catch (\Exception $e) {
            $entityManager->rollback();

            throw new \RuntimeException('Erro ao salvar usuário: ' . $e->getMessage(), 0, $e);
        }
    }
}
