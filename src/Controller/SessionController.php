<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\ProgrammeType;
use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\ProgrammeRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



final class SessionController extends AbstractController
{


        #[Route('/session', name: 'app_session')]
        public function index(SessionRepository $sessionRepository): Response
        {
    // Récupérer les sessions en cours, à venir et passées depuis le repository
            $sessionsEnCours = $sessionRepository->sessionsEnCours();
            $sessionsAVenir = $sessionRepository->sessionsAVenir();
            $sessionsPassees = $sessionRepository->sessionsPassees();

            return $this->render('session/index.html.twig', [
                'sessionsEnCours' => $sessionsEnCours,
                'sessionsAVenir' => $sessionsAVenir,
                'sessionsPassees' => $sessionsPassees,
            ]);
        }


    // CREER une nouvelle session ou MODIFIER une session existante
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


    // SUPPRIMER UNE SESSION
        #[Route('/session/{id}/delete', name: 'app_session_delete')]
        public function delete(Session $session, EntityManagerInterface $entityManager): Response
        {
            // Retirer les stagiaires de la session (désinscrire sans supprimer)
            foreach ($session->getStagiaires() as $stagiaire) {
                // Désinscrire chaque stagiaire de cette session
                $session->removeStagiaire($stagiaire);
            }
        
            // Supprimer tous les programmes liés à la session
            foreach ($session->getProgrammes() as $programme) {
                $entityManager->remove($programme);
            }
        
            // Supprimer la session
            $entityManager->remove($session);
            $entityManager->flush();
        
            // Rediriger vers la liste des sessions après suppression
            return $this->redirectToRoute('app_session');
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
            $stagiairesNonInscrits = $this->getStagiairesNonInscrits($sr, $session);

            // 2️⃣ Créer le formulaire pour ajouter un programme
            $form = $this->createForm(ProgrammeType::class, new Programme());

            // 3️⃣ Gérer la création du programme et récupérer la réponse du formulaire
            $this->handleProgrammeForm($form, $request, $entityManager, $session);

            // 4️⃣ Rendre la vue avec le formulaire et les stagiaires
            return $this->render('session/show.html.twig', [
                'session' => $session,
                'stagiairesNonInscrits' => $stagiairesNonInscrits,
                'form' => $form->createView(),
            ]);
        }


    // Méthode pour récupérer les stagiaires non inscrits à une session
        private function getStagiairesNonInscrits(StagiaireRepository $sr, Session $session)
        {
            return $sr->findStagiairesNonInscrits($session->getId());
        }



// ADD & REMOVE UN PROGRAMME dans UNE SESSION

    // Méthode pour ajouter un programme dans une session
        #[Route('/session/{id}/add_programme', name: 'app_session_add_programme', methods: ['POST'])]
        public function addProgramme(Session $session, Request $request, EntityManagerInterface $entityManager): Response
        {
            $form = $this->createForm(ProgrammeType::class, new Programme());
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $programme = $form->getData();

                // Vérifier si le module est déjà ajouté
                $existingProgramme = $session->getProgrammes()->filter(function ($prog) use ($programme) {
                    return $prog->getModule() === $programme->getModule();
                })->first();

                if ($existingProgramme) {
                    $this->addFlash('error', 'Ce module est déjà ajouté à cette session.');
                } else {
                    $session->addProgramme($programme);
                    $entityManager->persist($programme);
                    $entityManager->flush();
                    $this->addFlash('success', 'Le programme a été ajouté avec succès.');
                }

                return $this->redirectToRoute('app_session_show', ['id' => $session->getId()]);
            }

            return $this->render('session/show.html.twig', [
                'form' => $form->createView(),
                'session' => $session,
            ]);
        }

        // Méthode pour gérer l'ajout d'un programme dans la session
         private function handleProgrammeForm($form, Request $request, EntityManagerInterface $entityManager, Session $session)
         {
             $form->handleRequest($request);
 
             if ($form->isSubmitted() && $form->isValid()) {
                 $programme = $form->getData();
 
                 // Vérifier si le module a déjà été ajouté à la session
                 $existingProgramme = $this->checkIfModuleExists($session, $programme);
 
                 if ($existingProgramme) {
                     $this->addFlash('error', 'Ce module est déjà ajouté à cette session.');
                 } else {
                     // Ajouter le programme à la session
                    //  $this->addProgrammeToSession($session, $programme, $entityManager);
                     $this->addFlash('success', 'Le programme a été ajouté avec succès.');
                 }
 
                 // Redirection vers la même page après l'ajout
                 return $this->redirectToRoute('app_session_show', ['id' => $session->getId()]);
             }
         }

         
            // Méthode pour vérifier si un module existe déjà dans la session
                private function checkIfModuleExists(Session $session, Programme $programme)
                {
                    return $session->getProgrammes()->filter(function ($prog) use ($programme) {
                        return $prog->getModule() === $programme->getModule();
                    })->first();
                }



    // Méthode pour supprimer un programme dans une session
        #[Route('/session/{sessionId}/remove_programme/{programmeId}', name: 'app_session_remove_programme')]
    public function removeProgramme(
        int $sessionId,
        int $programmeId,
        SessionRepository $sessionRepository,
        ProgrammeRepository $programmeRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $session = $sessionRepository->find($sessionId);
        $programme = $programmeRepository->find($programmeId);

        if (!$session || !$programme) {
            throw $this->createNotFoundException('Session ou programme introuvable.');
        }

        if (!$session->getProgrammes()->contains($programme)) {
            throw $this->createNotFoundException('Ce programme ne fait pas partie de cette session.');
        }

        // Supprimer le programme de la session
        $session->removeProgramme($programme);
        $entityManager->remove($programme); // Supprime le programme en base
        $entityManager->flush();

        $this->addFlash('success', 'Le programme a été supprimé avec succès.');

        return $this->redirectToRoute('app_session_show', ['id' => $session->getId()]);
    }

    
    
    }