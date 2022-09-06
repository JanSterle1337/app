<?php 
namespace App\Repository;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SQLRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserTicketsForAllEvents($userEmail)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
           'SELECT u, t
           FROM App\Entity\User u
           INNER JOIN u.tickets t
           WHERE u.email = :email' 
        )->setParameter('email', $userEmail);
        
        return $query->getOneOrNullResult();
    }
}