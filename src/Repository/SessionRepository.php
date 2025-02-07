<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{


        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Session::class);
        }


    // Récupérer les sessions en cours
        public function sessionsEnCours(): array
        {
            return $this->createQueryBuilder('s')
                ->where('s.dateDebut <= :today')
                ->andWhere('s.dateFin >= :today')
                ->setParameter('today', (new \DateTime())->setTime(0, 0, 0)) 
                ->orderBy('s.dateDebut', 'ASC')
                ->getQuery()
                ->getResult();
        }

    // Récupérer les sessions à venir
        public function sessionsAVenir(): array
        {
            return $this->createQueryBuilder('s')
                ->where('s.dateDebut > :today')
                ->setParameter('today', (new \DateTime())->setTime(0, 0, 0)) 
                ->orderBy('s.dateDebut', 'ASC')
                ->getQuery()
                ->getResult();
        }

    // Récupérer les sessions passées
        public function sessionsPassees(): array
        {
            return $this->createQueryBuilder('s')
                ->where('s.dateFin < :today')
                ->setParameter('today', (new \DateTime())->setTime(0, 0, 0)) 
                ->orderBy('s.dateFin', 'DESC') // Trier par date de fin décroissante
                ->getQuery()
                ->getResult();
        }








    //    /**
    //     * @return Session[] Returns an array of Session objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Session
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
