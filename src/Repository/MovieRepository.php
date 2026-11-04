<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @return Movie[] Returns an array of Movie objects
     */
    public function findMovie(?string $title, ?int $year)
   {
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.title', 'ASC')
            ->setMaxResults(5);

        if ($title) {
            $qb
           ->andWhere('m.title = :title')
           ->setParameter('title', $title);
           
        }

        if ($year) {
            $qb 
            ->andWhere('m.year = :year')
            ->setParameter('year', $year);
            
        }

        return $qb->getQuery()->getResult();



//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    }
}