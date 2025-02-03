<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class StagiaireController extends AbstractController
{

    // Afficher la liste des stagiaires de la bdd avec findby
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {

        $stagiaires = $stagiaireRepository->findBy([], ['nom'=>'ASC']);
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }


    // Créer un nouveau stagiaire ou modifier un stagiaire existant
    #[Route('/stagiaire/new', name: 'new_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function new_stagiaire(Stagiaire $stagiaire = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$stagiaire) { 
            $stagiaire = new Stagiaire();
        }

        $form = $this->createForm(StagiaireType::class, $stagiaire);

        // soumisson du formulaire et insertion en bdd
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $stagiaire = $form->getData();

            $entityManager->persist($stagiaire); // persist (= prepare, en PDO)
            // actually executes the queries (INSERT query)
            $entityManager->flush(); // flush: envoyer en  bdd (= execute, en PDO)


    return $this->redirectToRoute('app_stagiaire');
    }

    return $this->render('stagiaire/new.html.twig', [
        'formAddStagiaire' => $form,
    ]);
    }

}