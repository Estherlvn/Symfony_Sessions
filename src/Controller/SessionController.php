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


    
    #[Route('/sessions/{id}', name: 'app_session_show')]
    public function show(int $id, SessionRepository $sessionRepository): Response
    {
        $session = $sessionRepository->find($id);

        if (!$session) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }



}
