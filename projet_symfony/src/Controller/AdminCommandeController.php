<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Commandes;
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
    public function index(CommandesRepository $commandesRepository, Request $request): Response
    {

        $search = $request->query->get('search');
        $options = [];
        if($nom_search = $request->query->get('nom_search')){
            $options['nom_search'] = $nom_search;
        }
        if($montant_search = $request->query->get('montant_search')){
            $options['montant_search'] = $montant_search;
        }
        if($date_search = $request->query->get('date_search')){
            $options['date_search'] = $date_search;
        }
        if($id_search = $request->query->get('id_search')){
            $options['id_search'] = $id_search;
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commandesRepository->getPaginator($offset, $search, $options);
        $nbrePages = ceil(count($paginator) / CommandesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + CommandesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CommandesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;

        return $this->render('admin_commande/index.html.twig', [
            'commandes' => $paginator,
            'previous' => $offset - CommandesRepository::PAGINATOR_PER_PAGE,
            'offset' => CommandesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }


    #[Route('/admin/commande/{id}', name: 'app_admin_commande_view')]
    public function view(ContenuRepository $contenuRepository, Commandes $commande, Clients $clients)
    {
        $contenu = $contenuRepository->findBy(['commandes' => $commande->getId()]);
  
        return $this->render('admin_commande/view.html.twig', [
            // 'contenus' => $contenu,
            'commandes' => $commande,
            'contenus' => $contenu,
            'client' => $clients
        ]);
    }
}
