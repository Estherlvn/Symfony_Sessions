<?php

namespace App\Controller;

use App\Repository\FormationRepository;
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


}

