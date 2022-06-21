<?php

namespace App\Controller;

use App\Entity\TypeCaracteristiques;
use App\Form\AddTypeCaracteristiqueFormType;
use App\Repository\CaracteristiquesRepository;
use App\Repository\TypeCaracteristiquesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTypeCaracteristiquesController extends AbstractController
{
    /**
     * page liste des caracteristiques
     *
     * @param TypeCaracteristiquesRepository $typeCaracteristiquesRepository
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/type/caracteristiques/update', name: 'TypeCaracteristiques')]
    public function indexup(TypeCaracteristiquesRepository $typeCaracteristiquesRepository, Request $request): Response
    {
        //on récupère toutes nos caractéristiques via leur nom
        $gettypeCaracteristiques = $typeCaracteristiquesRepository->getListTypeCaracteristique();
        //on vérifie si il y a une recherche dans la query string
        $typeCaracteristiquesSearch = $request->query->get('typeCaracteristiquesSearch', '');
        //paginator
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $typeCaracteristiquesRepository->getTypeCaracteristiquesPaginator($offset, $typeCaracteristiquesSearch);
        $nbrePages = ceil(count($paginator) / TypeCaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + TypeCaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / TypeCaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;

        return $this->render('admin_type_caracteristiques/modifyShow.html.twig', [
            'gettypeCaracteristiques' => $gettypeCaracteristiques,
            'typeCaracteristiques' => $paginator,
            'previous' => $offset - TypeCaracteristiquesRepository::PAGINATOR_PER_PAGE,
            'offset' => TypeCaracteristiquesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }


    /**
     * page d'édition d'une caracteristique
     *
     * @param CaracteristiquesRepository $caracteristiquesRepository
     * @param TypeCaracteristiques $typeCaracteristiques
     * @param TypeCaracteristiquesRepository $typeCaracteristiquesRepository
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/type/caracteristiques{id}', name: 'update_type_caracteristiques')]
    public function modifyTypeCaract(CaracteristiquesRepository $caracteristiquesRepository, TypeCaracteristiques $typeCaracteristiques, TypeCaracteristiquesRepository $typeCaracteristiquesRepository, Request $request): Response
    {
        //on récupère es caractéristiques correspondantes au type de caractéristiques
        $query = $caracteristiquesRepository->findBy(['typeCaracteristiques' => $typeCaracteristiques]);
        //on crée le formulaire de modification
        $formTypeCaract = $this->createForm(AddTypeCaracteristiqueFormType::class, $typeCaracteristiques);
        //si il est soumis on recupère la request
        $formTypeCaract->handleRequest($request);
        //si soumis et valide
        if ($formTypeCaract->isSubmitted() && $formTypeCaract->isValid()) {
            //on modifie la carac en bdd
            $typeCaracteristiquesRepository->add($typeCaracteristiques, true);
            //on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'Le type de caracteristiques a bien été modifié');
            //on redirige vers la liste des caracs
            return $this->redirectToRoute('TypeCaracteristiques');
        }
        return $this->render('admin_type_caracteristiques/modify.html.twig', [
            'form_type_caract' => $formTypeCaract->createView(),
            'getcaracteristiques' => $query
        ]);
    }

    /**
     * page ajout de caracteristiques
     *
     * @param TypeCaracteristiquesRepository $typeCaracteristiquesRepository
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/type/caracteristiques/add', name: 'app_admin_type_caracteristiques')]
    public function addTypeCaract(TypeCaracteristiquesRepository $typeCaracteristiquesRepository, Request $request): Response
    {
        //on instancie une nouvelle caracteristique
        $addtypeCaract = new TypeCaracteristiques();
        //on crée le formulaire de création
        $formTypeCaract = $this->createForm(AddTypeCaracteristiqueFormType::class, $addtypeCaract);
        //si soumis on récupre la request
        $formTypeCaract->handleRequest($request);
        //on récupère les caractéristiques en fonction des types de caracteristiques
        $gettypeCaracteristiques = $typeCaracteristiquesRepository->getListTypeCaracteristique();
        //si soumis et valide
        if ($formTypeCaract->isSubmitted() && $formTypeCaract->isValid()) {
            //on ajoute la caracteristique en bdd
            $typeCaracteristiquesRepository->add($addtypeCaract, true);
            //on ajoute un message flash avec le flag info
            $this->addFlash('info', 'Le nouveau type de caracteristiques a bien été ajouté');
            //on redirige vers la même page
            return $this->redirectToRoute('app_admin_type_caracteristiques');
        }
        return $this->render('admin_type_caracteristiques/index.html.twig', [
            'controller_name' => 'AdminTypeCaracteristiquesController',
            'type_caract' => $typeCaracteristiquesRepository,
            'form_type_caract' => $formTypeCaract->createView(),
            'gettypeCaracteristiques' => $gettypeCaracteristiques
        ]);
    }

    /**
     * url de suppresion
     *
     * @param TypeCaracteristiquesRepository $typeCaracteristiquesRepository
     * @param TypeCaracteristiques $typeCaracteristiques
     * @return Response
     */
    #[Route('/admin/type/caracteristiques/delete/{id}', name: 'delete_type_caracteristiques')]
    public function delConference(TypeCaracteristiquesRepository $typeCaracteristiquesRepository, TypeCaracteristiques $typeCaracteristiques): Response
    {
        //on supprime la caracteristique de la bdd
        $typeCaracteristiquesRepository->remove($typeCaracteristiques, true);
        //on ajoute un message flash avec le flag info
        $this->addFlash('info', 'Le type de caracteristiques a bien été supprimé');
        //on redirige vers la page liste
        return $this->redirectToRoute('TypeCaracteristiques');
    }
}
