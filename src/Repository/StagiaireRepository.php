<?php

namespace App\Repository;

use App\Entity\Stagiaire;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Stagiaire>
 */
class StagiaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stagiaire::class);
    }

    // Récupérer tous les stagiaires non-inscrits dans une formation donnée
        public function findStagiairesNonInscrits(int $sessionId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM stagiaire s
            WHERE s.id NOT IN (
                SELECT stagiaire_id FROM session_stagiaire WHERE session_id = :sessionId
            )
        ';

        try {
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery(['sessionId' => $sessionId]);
            return $resultSet->fetchAllAssociative();
        } catch (\Exception $e) {
            return [];
        }
    }


    //    /**
    //     * @return Stagiaire[] Returns an array of Stagiaire objects
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

    //    public function findOneBySomeField($value): ?Stagiaire
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
