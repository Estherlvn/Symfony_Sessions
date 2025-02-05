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

    // // Récupérer tous les stagiaires non-inscrits dans une session donnée SQL
    //     public function findStagiairesNonInscrits(int $sessionId): array
    // {
    //     $conn = $this->getEntityManager()->getConnection();

    //     $sql = '
    //         SELECT * FROM stagiaire s
    //         WHERE s.id NOT IN (
    //             SELECT stagiaire_id FROM session_stagiaire WHERE session_id = :sessionId
    //         )
    //     ';

    //     try {
    //         $stmt = $conn->prepare($sql);
    //         $resultSet = $stmt->executeQuery(['sessionId' => $sessionId]);
    //         return $resultSet->fetchAllAssociative();
    //     } catch (\Exception $e) {
    //         return [];
    //     }
    // }

     // Récupérer tous les stagiaires non-inscrits dans une session donnée DQL
     public function findStagiairesNonInscrits($session_id)
     {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les stagiaires d'une session dont l'id est passé en paramètre
        $qb->select('s')
            ->from('App\Entity\Stagiaire', 's')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id = :id');

        $sub = $em->createQueryBuilder();
        // sélectionner tous les stagiaires qui NE SONT PAS (NOT IN) dans le résultat précédent
        // on obtient donc les stagiares non-inscrits pour une session définie
        $sub->select('st')
            ->from('App\Entity\Stagiaire', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            // requête paramétrée
            ->setParameter('id', $session_id)
            // trier la liste des stagiaires sur le nom de famille
            -> orderBy('st.nom');

        // renvoyer le résultat
        $query = $sub->getQuery();
        return $query->getResult();
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
