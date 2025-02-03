<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\FormateurType;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FormateurController extends AbstractController
{

        // Afficher la liste des formateurs de la bdd avec findby
        #[Route('/formateur', name: 'app_formateur')]

        public function index(FormateurRepository $formateurRepository): Response

        {
            $formateurs = $formateurRepository->findBy([], ['nom'=>'ASC']); 
            return $this->render('formateur/index.html.twig', [
                'formateurs' => $formateurs

            ]);
        }

    
        // Créer un nouveau formateur ou modifier un formateur existant
        #[Route('/formateur/new', name: 'new_formateur')]
        #[Route('/formateur/{id}/edit', name: 'edit_formateur')]
        public function new_formateur(Formateur $formateur = null, Request $request, EntityManagerInterface $entityManager): Response
        {

            if(!$formateur) { 
                $formateur = new Formateur(); // Crée un nouveau formateur
            }

            $form = $this->createForm(FormateurType::class, $formateur); // Associe le formulaire du formateur

            // Soumission du formulaire et insertion en bdd
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $formateur = $form->getData();

                $entityManager->persist($formateur); // persist (= prepare, en PDO)
                // Exécute les requêtes (INSERT)
                $entityManager->flush(); // flush: envoyer en bdd (= execute)

                return $this->redirectToRoute('app_formateur'); // Redirection vers la liste des formateurs
            }

            return $this->render('formateur/new.html.twig', [
                'formAddFormateur' => $form, // Le formulaire sera envoyé à la vue
            ]);
        }


}
