<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\ProgrammeType;
use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



final class SessionController extends AbstractController
{

    // Afficher toutes les sessions de la BDD
        #[Route('/session', name: 'app_session')]
        public function index(SessionRepository $sessionRepository): Response
        {

            $sessions = $sessionRepository->findBy([], ['dateDebut'=>'ASC']);
            return $this->render('session/index.html.twig', [
                'sessions' => $sessions,
            ]);
        }
    

    // CREER une nouvelle session ou modifier une session existante
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


    // SUPPRESSION d'un stagiaire inscrit dans une session donnée
        // Utiliser des tirets (-) dans les URLs comme dans "remove-stagiaire"
        // Dans le nom de la route (name), on garde le snake_case (_)
        #[Route('/session/{session}/remove-stagiaire/{stagiaire}', name: 'session_remove_stagiaire')]
        public function removeStagiaire(Session $session, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
        {
            if ($session->getStagiaires()->contains($stagiaire)) {
                $session->removeStagiaire($stagiaire);
                $entityManager->persist($session);
                $entityManager->flush();
        
                $this->addFlash('success', 'Le stagiaire a été retiré de la session.');
            } else {
                $this->addFlash('warning', 'Ce stagiaire ne fait pas partie de cette session.');
            }
        
            return $this->redirectToRoute('app_session_show', ['id' => $session->getId()]);
        }

        
    // AJOUT d'un stagiaire dans une session donnée
        #[Route('/session/{id}/add-stagiaire', name: 'session_add_stagiaire', methods: ['POST'])]
        public function addStagiaire(Session $session, Request $request, EntityManagerInterface $entityManager, StagiaireRepository $stagiaireRepository): Response
        {
            $stagiaireId = $request->request->get('stagiaireId');

            // Récupérer le stagiaire
            $stagiaire = $stagiaireRepository->find($stagiaireId);

            // Ajouter le stagiaire à la session
            if (!$session->getStagiaires()->contains($stagiaire)) {
                $session->addStagiaire($stagiaire);
                $entityManager->persist($session);
                $entityManager->flush();

                $this->addFlash('success', 'Le stagiaire a été ajouté avec succès.');
            } else {
                $this->addFlash('warning', 'Ce stagiaire est déjà inscrit dans cette session.');
            }

            return $this->redirectToRoute('app_session_show', ['id' => $session->getId()]);
        }

     
    // Affichage de la session et des stagiaires non inscrits, création du programme
    #[Route('/session/{id<\d+>}', name: 'app_session_show')]
    public function show(
        Session $session,
        StagiaireRepository $sr,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // 1️⃣ Récupérer les stagiaires non inscrits
        $stagiairesNonInscrits = $sr->findStagiairesNonInscrits($session->getId());
    
        // 2️⃣ Créer le formulaire pour ajouter un programme
        $form = $this->createForm(ProgrammeType::class, new Programme());
    
        // 3️⃣ Gérer la création du programme et récupérer la réponse du formulaire
        $response = $this->handleProgrammeCreation($session, $request, $entityManager, $form);
    
        if ($response instanceof Response) {
            return $response; // Redirection si le formulaire a été soumis et validé
        }
    
        // 4️⃣ Rendre la vue avec le formulaire
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiairesNonInscrits' => $stagiairesNonInscrits,
            'form' => $form->createView(),
        ]);
    }
    

    // Création d'un nouveau programme pour une session
    private function handleProgrammeCreation(
        Session $session,
        Request $request,
        EntityManagerInterface $entityManager,
        FormInterface $form // Ajouter le formulaire passé de la méthode show
    ): ?Response {
        $form->handleRequest($request); // Utiliser directement le formulaire passé
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Créer un objet Programme à partir des données du formulaire
            $programme = $form->getData();
            
            // Associer le programme à la session via la méthode addProgramme
            $session->addProgramme($programme);
    
            // Enregistrer le programme en base de données
            $entityManager->persist($programme);
            $entityManager->flush();
    
            // Ajouter un message flash de succès
            $this->addFlash('success', 'Le programme a été ajouté avec succès.');
    
            // Redirection après ajout du programme
            return $this->redirectToRoute('app_session_show', ['id' => $session->getId()]);
        }
    
        return null; // Retourner null si le formulaire n'est pas soumis ou valide
    }
    
}