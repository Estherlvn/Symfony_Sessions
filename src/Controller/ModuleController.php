<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
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
}
