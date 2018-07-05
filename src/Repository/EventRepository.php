<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // select pour la recherche sur le name de l'event
    public function findByName($name)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    // select avec date de l'évenement > à now
    public function countFutureEvents(): int 
    {
        return $this->createQueryBuilder('e')
         ->select('COUNT(e.id)')
         ->andwhere('e.start_at > CURRENT_TIME()')
         // ou ->andwhere('e.start_at > :now')
         // ->setParameter('now', \DateTime())
         ->getQuery()
         ->getSingleScalarResult() // renvoi un entier, sans le single renvoie un tableau 
         ;
    }

    public function getRandom() {
        $stmt = $this->createQueryBuilder('e');

        $stmt->orderBy('RAND()');
        $stmt->setMaxResults(1);

        return $stmt->getQuery()->getOneOrNullResult();

    }
    

}
