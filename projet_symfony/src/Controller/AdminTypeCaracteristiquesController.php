<?php

namespace App\Controller;

use App\Entity\TypeCaracteristiques;
use App\Form\AddTypeCaracteristiqueFormType;
use App\Repository\TypeCaracteristiquesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTypeCaracteristiquesController extends AbstractController
{
    #[Route('/admin/type/caracteristiques', name: 'TypeCaracteristiques')]
    public function index(TypeCaracteristiquesRepository $typeCaracteristiquesRepository,Request $request): Response
    {
        $gettypeCaracteristiques = $typeCaracteristiquesRepository->getListTypeCaracteristique();
        $optionTypeCaracteristiques = $request->query->get('optionTypeCaracteristiques','');
        $typeCaracteristiquesSearch = $request->query->get('typeCaracteristiquesSearch','');
        $offset = max(0,$request->query->getInt('offset',0));
        $paginator = $typeCaracteristiquesRepository->getTypeCaracteristiquesPaginator($offset,$optionTypeCaracteristiques,$typeCaracteristiquesSearch);
        return $this->render('admin_type_caracteristiques/modify.html.twig', [
            'gettypeCaracteristiques' => $gettypeCaracteristiques,
            'searchTypeCaracteristiques'=>$optionTypeCaracteristiques,
            'typeCaracteristiques' => $paginator,
            'previous' => $offset - TypeCaracteristiquesRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator),$offset + TypeCaracteristiquesRepository::PAGINATOR_PER_PAGE)
        ]);
    }

    #[Route('/admin/type/caracteristiques/add', name: 'app_admin_type_caracteristiques')]
    public function addTypeCaract(TypeCaracteristiquesRepository $typeCaracteristiquesRepository,Request $request): Response
    {
        $addtypeCaract = new TypeCaracteristiques();
        $formTypeCaract = $this->createForm(AddTypeCaracteristiqueFormType::class,$addtypeCaract);
        $formTypeCaract->handleRequest($request);
        if ($formTypeCaract->isSubmitted() && $formTypeCaract->isValid())
        {
            $typeCaracteristiquesRepository->add($addtypeCaract,true);
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin_type_caracteristiques/index.html.twig', [
            'controller_name' => 'AdminTypeCaracteristiquesController',
            'type_caract'=> $typeCaracteristiquesRepository,
            'form_type_caract'=> $formTypeCaract->createView(),
        ]);
    }
    #[Route('/admin/type/caracteristiques{id}/delete', name: 'delete_caracteristiques')]
    public function delConference(TypeCaracteristiquesRepository $typeCaracteristiquesRepository,TypeCaracteristiques $typeCaracteristiques): Response
    {
        $typeCaracteristiquesRepository->remove($typeCaracteristiques,true);
        return $this->redirectToRoute('app_admin');
    }
}
