<?php

namespace App\Controller;

use App\Entity\Contenu;
use App\Repository\ClientsRepository;
use App\Repository\ContenuRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommandeController extends AbstractController
{
    #[Route('/admin/commande', name: 'app_admin_commande')]
    public function index(CommandesRepository $commandesRepository, Request $request): Response
    {
        $commandes = $commandesRepository->findAll();

        $tabCommande = [];
        foreach($commandes as $commande) {
            $tabCommande[] = ["id"=> $commande->getId(),"date"=>$commande->getCreatedAt(),"client"=>$commande->getClient()];
        }

        return $this->render('admin_commande/index.html.twig', [
            'commandes' => $tabCommande,
        ]);
    }

    #[Route('/admin/commande/{id}', name: 'app_admin_commande_view')]
    public function view(CommandesRepository $commandesRepository, ContenuRepository $contenuRepository)
    {
        $commande = $commandesRepository->findAll();
        // $contenu = $commande->getContenu();

        return $this->render('admin_commande/view.html.twig', [
            // 'contenus' => $contenu,
            'commandes' => $commande,
        ]);
    }
}
