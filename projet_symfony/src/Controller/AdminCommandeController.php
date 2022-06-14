<?php

namespace App\Controller;

use App\Repository\CommandesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommandeController extends AbstractController
{
    #[Route('/admin/commande', name: 'app_admin_commande')]
    public function index(CommandesRepository $commandesRepository): Response
    {
        $commande = $commandesRepository->findAll();
        return $this->render('admin_commande/index.html.twig', [
            'commandes' => $commande
        ]);
    }
}
