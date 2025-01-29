<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {

        $sessions = $sessionRepository->findBy([], ['dateDebut'=>'ASC']);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }
}
