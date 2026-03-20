<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findJeuxApprouvesSansModifEnAttente()
    {
        return $this->createQueryBuilder('g')
            ->where('g.isValidated = true') // On prend les jeux approuvés
            // Et on EXCLUT ceux dont l'ID se trouve dans les GameUpdate "pending"
            // IDENTITY(gu.game) permet de récupérer l'ID du jeu
            ->andWhere('g.id NOT IN (
                SELECT IDENTITY(gu.game) 
                FROM App\Entity\GameUpdate gu 
                JOIN gu.status s 
                WHERE s.name = :status
            )')
            ->setParameter('status', 'pending')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Jeu[] Returns an array of Jeu objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Jeu
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
