<?php

namespace App\Repository;

use App\Entity\GameRound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameRound>
 *
 * @method GameRound|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameRound|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameRound[]    findAll()
 * @method GameRound[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRoundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameRound::class);
    }

    public function add(GameRound $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GameRound $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneById($id): ?GameRound
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByIsPlayedYet($playedAlready): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.playedAlready = :val')
            ->setParameter('val', $playedAlready)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findNotPlayedYet()
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.playedAlready = :val')
            ->setParameter('val', false)
            ->getQuery()
            ->getResult();
    }

    public function findNotPlayedYetBySlug($slug): ?GameRound
    {
        return $this->createQueryBuilder('g')
        ->andWhere('g.name = :slug')
        ->andWhere('g.playedAlready = :played')
        ->setParameter('slug', $slug)
        ->setParameter('played', false)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
 
//    /**
//     * @return GameRound[] Returns an array of GameRound objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GameRound
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
