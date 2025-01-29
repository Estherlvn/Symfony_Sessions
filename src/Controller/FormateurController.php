<?php

namespace App\Controller;

use App\Repository\FormateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FormateurController extends AbstractController
{
    #[Route('/formateur', name: 'app_formateur')]

    public function index(FormateurRepository $formateurRepository): Response

    {

        $formateurs = $formateurRepository->findBy([], ['nom'=>'ASC']); 
        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs

        ]);
    }
}
