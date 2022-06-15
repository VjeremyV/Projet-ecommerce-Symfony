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

    #[Route('/admin/produit/update{nom_search}', name: 'admin_produit_updateListe')]
    public function indexup(CommandesRepository $produitRepository, Request $request, $nom_search = ''): Response
    {
        $search = $request->query->get('search');
        $options = [];
        if ($id_search = $request->query->get('id_search')) {
            $options['id_search'] = $id_search;
        }
        if ($nom_search = $request->query->get('nom_search')) {
            $options['nom_search'] = $nom_search;
        }
        if ($montant_search = $request->query->get('montant_search')) {
            $options['montant_search'] = $montant_search;
        }

        //pagination
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $produitRepository->getPaginator($offset, $search, $options);
        $nbrePages = ceil(count($paginator) / CommandesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + CommandesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CommandesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;
        return $this->render('admin_commande/index.html.twig', [
            'listcommande' => $paginator,
            'search' => $search,
            'previous' => $offset - CommandesRepository::PAGINATOR_PER_PAGE,
            'offset' => CommandesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
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
