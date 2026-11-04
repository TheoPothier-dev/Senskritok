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
        //configuration de l'affichage si aucun tri n'est effectué
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.title', 'ASC')
            ->setMaxResults(5);

        //filtre en fonction du titre
        if ($title) {
            $qb
           ->andWhere('m.title = :title')
           ->setParameter('title', $title);
           
        }

        //filtre en fonction de l'année
        if ($year) {
            $qb 
            ->andWhere('m.year = :year')
            ->setParameter('year', $year);
            
        }

        return $qb->getQuery()->getResult();

    }
}