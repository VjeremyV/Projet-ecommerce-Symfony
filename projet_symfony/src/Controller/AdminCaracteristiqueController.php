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
    #[Route('/admin/caracteristiques/update', name: 'Caracteristiques')]
    public function indexup(CaracteristiquesRepository $caracteristiquesRepository,Request $request): Response
    {
        $getCaracteristiques = $caracteristiquesRepository->getListCaracteristique();
        $CaracteristiquesSearch = $request->query->get('CaracteristiquesSearch','');
        //paginator
        $offset = max(0,$request->query->getInt('offset',0));
        $paginator = $caracteristiquesRepository->getCaracteristiquesPaginator($offset, $CaracteristiquesSearch);
        $nbrePages = ceil(count($paginator) / CaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator),$offset + CaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;

        return $this->render('admin_caracteristique/modifyShow.html.twig', [
            'getCaracteristiques' => $getCaracteristiques,
            'Caracteristiques' => $paginator,
            'previous' => $offset - CaracteristiquesRepository::PAGINATOR_PER_PAGE,
            'offset'=> CaracteristiquesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }

    #[Route('/admin/caracteristiques{id}', name: 'update_caracteristiques')]
    public function modifyCaract(Caracteristiques $caracteristiques, CaracteristiquesRepository $caracteristiquesRepository, Request $request): Response
    {
        $formCaract = $this->createForm(CaracteristiqueAddFormType::class, $caracteristiques);
        $formCaract->handleRequest($request);
        if ($formCaract->isSubmitted() && $formCaract->isValid())
        {
            $caracteristiquesRepository->add($caracteristiques,true);
            $this->addFlash('info', 'La caracteristiques a bien été modifié');
            return $this->redirectToRoute('TypeCaracteristiques');
        }
        return $this->render('admin_caracteristique/modify.html.twig', [
            'form_type_caract'=>$formCaract->createView(),
        ]);
    }

    #[Route('/admin/caracteristiques/delete', name: 'Caracteristiquesdel')]
    public function indexdel(CaracteristiquesRepository $caracteristiquesRepository,Request $request): Response
    {
        $getCaracteristiques = $caracteristiquesRepository->getListCaracteristique();
//        $optionCaracteristiques = $request->query->get('optionCaracteristiques','');
        $CaracteristiquesSearch = $request->query->get('CaracteristiquesSearch','');
//paginator
        $offset = max(0,$request->query->getInt('offset',0));
        $paginator = $caracteristiquesRepository->getCaracteristiquesPaginator($offset,$CaracteristiquesSearch);
        $nbrePages = ceil(count($paginator) / CaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator),$offset + CaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CaracteristiquesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;
        return $this->render('admin_caracteristique/deleteShow.html.twig', [
            'getCaracteristiques' => $getCaracteristiques,
//            'searchTypeCaracteristiques'=>$optionCaracteristiques,
            'Caracteristiques' => $paginator,
            'previous' => $offset - CaracteristiquesRepository::PAGINATOR_PER_PAGE,
            'offset'=> CaracteristiquesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }

    #[Route('/admin/caracteristiques/add', name: 'app_admin_caracteristiques')]
    public function addCaract(CaracteristiquesRepository $caracteristiquesRepository,Request $request): Response
    {
        $addCaract = new Caracteristiques();
        $formCaract = $this->createForm(CaracteristiqueAddFormType::class,$addCaract);
        $formCaract->handleRequest($request);
        if ($formCaract->isSubmitted() && $formCaract->isValid())
        {
            $caracteristiquesRepository->add($addCaract,true);
            $this->addFlash('info', 'La nouvelle caracteristique a bien été ajouté');
            return $this->redirectToRoute('app_admin_caracteristiques');
        }
        return $this->render('admin_caracteristique/index.html.twig', [
            'controller_name' => 'AdminCaracteristiquesController',
            'caract'=> $caracteristiquesRepository,
            'form_caract'=> $formCaract->createView(),
        ]);
    }

    #[Route('/admin/caracteristiques/delete/{id}', name: 'delete_caracteristiques')]
    public function delConference(CaracteristiquesRepository $caracteristiquesRepository,Caracteristiques $caracteristiques): Response
    {
        $caracteristiquesRepository->remove($caracteristiques,true);
        $this->addFlash('info', 'La caracteristique a bien été supprimé');
        return $this->redirectToRoute('Caracteristiquesdel');
    }
}
