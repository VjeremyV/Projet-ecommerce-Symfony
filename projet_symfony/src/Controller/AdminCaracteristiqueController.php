<?php

namespace App\Controller;

use App\Entity\Caracteristiques;
use App\Form\CaracteristiqueAddFormType;
use App\Repository\CaracteristiquesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCaracteristiqueController extends AbstractController
{
    /**
     * page de la liste des caracteristiques
     *
     * @param CaracteristiquesRepository $caracteristiquesRepository
     * @param Request $request
     * @param string $type_Caracteristiques_search
     * @param string $nom_search
     * @param string $offset
     * @return Response
     */
    #[Route('/admin/caracteristiques/update', name: 'Caracteristiques')]
    public function indexup(CaracteristiquesRepository $caracteristiquesRepository, Request $request, $type_Caracteristiques_search = '', $nom_search = '', $offset = ''): Response
    {
        // on utilise la methode getListCaracteristique pour récuperer les noms des caracteristiques 
        $getCaracteristiques = $caracteristiquesRepository->getListCaracteristique();
        // on met dans une variable le contenu de la query string 'CaracteristiquesSearch', pour vérifier si il y a une recherche en cours
        $CaracteristiquesSearch = $request->query->get('CaracteristiquesSearch', '');
        //on créée une variable  otpions qui va contenir nos otpions de filtres si il y en a
        $options = [];
        //on vérifie dans la query string si il y a des filtres d'activés et on les ajoute dans notre tableau d'options
        if ($nom_search = $request->query->get('nom_search')) {
            $options['nom_search'] = $nom_search;
        }
        if ($type_Caracteristiques_search = $request->query->get('type_Caracteristiques_search')) {
            $options['type_Caracteristiques_search'] = $type_Caracteristiques_search;
        }

        //paginator
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $caracteristiquesRepository->getCaracteristiquesPaginator($offset, $CaracteristiquesSearch, $options);
        $nbrePages = ceil(count($paginator) / CaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + CaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;

        return $this->render('admin_caracteristique/modifyShow.html.twig', [
            'getCaracteristiques' => $getCaracteristiques,
            'Caracteristiques' => $paginator,
            'previous' => $offset - CaracteristiquesRepository::PAGINATOR_PER_PAGE,
            'offset' => CaracteristiquesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }

    /**
     * page d'édition d'une caractéristique
     */
    #[Route('/admin/caracteristiques{id}', name: 'update_caracteristiques')]
    public function modifyCaract(Caracteristiques $caracteristiques, CaracteristiquesRepository $caracteristiquesRepository, Request $request): Response
    {
        // on crée le formulaire pour modifier les caractéristiques
        $formCaract = $this->createForm(CaracteristiqueAddFormType::class, $caracteristiques);
        //si il est soumis on récupère les données de la requête
        $formCaract->handleRequest($request);
        //si il est soumis et valide
        if ($formCaract->isSubmitted() && $formCaract->isValid()) {
            //on met à jour la caractéristique
            $caracteristiquesRepository->add($caracteristiques, true);
            //on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'La caracteristiques a bien été modifié');
            // on redirige vers la route qui liste les caracteristiques
            return $this->redirectToRoute('Caracteristiques');
        }
        return $this->render('admin_caracteristique/modify.html.twig', [
            'form_caract' => $formCaract->createView(),
        ]);
    }

    /**
     * page ajout d'une caractéristique
     *
     * @param CaracteristiquesRepository $caracteristiquesRepository
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/caracteristiques/add', name: 'app_admin_caracteristiques')]
    public function addCaract(CaracteristiquesRepository $caracteristiquesRepository, Request $request): Response
    {
        //on instancie un nouvelle caracteristique
        $addCaract = new Caracteristiques();
        //on créée le formulaire de création
        $formCaract = $this->createForm(CaracteristiqueAddFormType::class, $addCaract);
        //si il est soumis on récupère les données de la requête
        $formCaract->handleRequest($request);
        //si il est soumis et valide
        if ($formCaract->isSubmitted() && $formCaract->isValid()) {
            // on ajoute la caracteristique en bdd
            $caracteristiquesRepository->add($addCaract, true);
            // on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'La nouvelle caracteristique a bien été ajouté');
            //on redirige vers la même page
            return $this->redirectToRoute('app_admin_caracteristiques');
        }
        return $this->render('admin_caracteristique/index.html.twig', [
            'controller_name' => 'AdminCaracteristiquesController',
            'caract' => $caracteristiquesRepository,
            'form_caract' => $formCaract->createView(),
        ]);
    }

    /**
     * page de suppression
     */
    #[Route('/admin/caracteristiques/delete/{id}', name: 'delete_caracteristiques')]
    public function delConference(CaracteristiquesRepository $caracteristiquesRepository, Caracteristiques $caracteristiques): Response
    {
        //on supprime la caracteristique
        $caracteristiquesRepository->remove($caracteristiques, true);
        //on ajoute un message flash avec le flag 'info'
        $this->addFlash('info', 'La caracteristique a bien été supprimé');
        //on redirige vers la page qui liste les caracs
        return $this->redirectToRoute('Caracteristiques');
    }
}
