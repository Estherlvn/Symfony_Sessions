<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FormationController extends AbstractController
{
        #[Route('/formation', name: 'app_formation')]
        public function index(FormationRepository $formationRepository): Response
        {

            $formations = $formationRepository->findBy([]);
            return $this->render('formation/index.html.twig', [
                'formations' => $formations,
            ]);
        }

    // Pour faire afficher les détails d'une formation
        #[Route('/formations/{id}', name: 'app_formation_show')]
        public function show(int $id, FormationRepository $formationRepository): Response
        {
            $formation = $formationRepository->find($id);

            if (!$formation) {
                throw $this->createNotFoundException('Formation non trouvée.');
            }

            return $this->render('formation/show.html.twig', [
                'formation' => $formation,
            ]);
        }


    // SUPPRESSION d'une session (dans une formation)
        #[Route('/formation/{formation}/remove-session/{session}', name: 'formation_remove_session')]
        public function removeSession(Formation $formation, Session $session, EntityManagerInterface $entityManager): Response
        {
            if ($formation->getSessions()->contains($session)) {
                $entityManager->remove($session); // Supprime complètement la session
                $entityManager->flush();
        
                $this->addFlash('success', 'La session a été supprimée.');
            } else {
                $this->addFlash('warning', 'Cette session ne fait pas partie de cette formation.');
            }
        
            return $this->redirectToRoute('app_formation_show', ['id' => $formation->getId()]);
        }
        

        

}

