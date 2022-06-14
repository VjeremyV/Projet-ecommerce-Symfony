<?php

namespace App\Controller;

use App\Entity\Contenu;
use App\Repository\ClientsRepository;
use App\Repository\ContenuRepository;
use App\Repository\CommandesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommandeController extends AbstractController
{
    #[Route('/admin/commande', name: 'app_admin_commande')]
    public function index(CommandesRepository $commandesRepository, ClientsRepository $clientsRepository, ContenuRepository $contenuRepository, Request $request): Response
    {
        $commande = $commandesRepository->findAll();
        $client = $clientsRepository->findAll();
        // $contenu = $contenuRepository->findAll();

        return $this->render('admin_commande/index.html.twig', [
            'commandes' => $commande,
            'clients' => $client,
            // 'contenus' => $contenu
        ]);
    }

    #[Route('/admin/commande/{id}', name: 'app_admin_commande_view')]
    public function view(CommandesRepository $commandesRepository, ClientsRepository $clientsRepository, ContenuRepository $contenuRepository)
    {
        // $contenu = $contenuRepository->findAll();
        $commande = $commandesRepository->findAll();
        $client = $clientsRepository->findAll();

        return $this->render('admin_commande/view.html.twig', [
            // 'contenus' => $contenu,
            'clients' => $client,
            'commandes' => $commande,
        ]);
    }
}
