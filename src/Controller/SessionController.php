<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    // Créer une nouvelle session ou modifier une session existante
    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_session(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$session) { 
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);

        // soumission du formulaire et insertion en bdd
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $session = $form->getData();

            $entityManager->persist($session); // persist (= prepare, en PDO)
            // actually executes the queries (INSERT query)
            $entityManager->flush(); // flush: envoyer en bdd (= execute, en PDO)

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig', [
            'formAddSession' => $form,
        ]);
    }



}


