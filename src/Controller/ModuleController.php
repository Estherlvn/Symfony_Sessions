<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(ModuleRepository $moduleRepository): Response
    {

        $modules = $moduleRepository->findBy([]);
        return $this->render('module/index.html.twig', [
            'modules' => $modules,
        ]);
    }


    #[Route('/new', name: 'new_module')]
    #[Route('/{id}/edit', name: 'edit_module', requirements: ['id' => '\d+'])]
    public function new_module(
        ?int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        ModuleRepository $moduleRepository
        ): Response {
        // Trouver le module s'il existe, sinon en créer un nouveau
        $module = $id ? $moduleRepository->find($id) : new Module();

        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($module);
            $entityManager->flush();

            return $this->redirectToRoute('app_module');
        }

        return $this->render('module/new.html.twig', [
            'formAddModule' => $form,
        ]);
    }
}

