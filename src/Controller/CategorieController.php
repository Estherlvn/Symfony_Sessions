<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CategorieController extends AbstractController
{
    // Afficher la liste des categories de la bdd avec findby
    #[Route('/categorie', name: 'app_categorie')]

    public function index(CategorieRepository $categorieRepository): Response

    {
        $categories = $categorieRepository->findBy([], ['nomCategorie'=>'ASC']); 
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories

        ]);
    }

     // Pour faire afficher les formation d'une categorie
    #[Route('/categorie/{id}', name: 'app_categorie_show')]
    public function show(int $id, categorieRepository $categorieRepository): Response
    {
        $categorie = $categorieRepository->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée.');
        }

        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }


}
