<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategorieAddFormType;
use App\Form\EditCategoryFormType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    
    #[Route('/admin/categories/add', name: 'app_admin_categories_add')]
    public function AddCategorie(Request $request, CategoriesRepository $categoriesRepository): Response
    {
        $categorie = new Categories();
        $form = $this->createForm(CategorieAddFormType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('info', 'La catégorie a bien été ajoutée');
            $categoriesRepository->add($categorie, true);
            return $this->redirectToRoute('app_admin_categories_add');
        }
        return $this->render('admin_categories/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/categories/update', name: 'categories_update_list')]
    public function indexUp(CategoriesRepository $categoriesRepository, Request $request)
    {
        $searchCategory = $request->query->get('search');

        //pagination
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $categoriesRepository->getPaginator($offset, $searchCategory);
        $nbrePages = ceil(count($paginator) / CategoriesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + CategoriesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CategoriesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;

        return $this->render('admin_categories/update_list.html.twig', [
            'listCategories' => $paginator,
            'searchCategory' => $searchCategory,
            'previous' => $offset - CategoriesRepository::PAGINATOR_PER_PAGE,
            'offset' => CategoriesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }

    #[Route('/admin/categories/update/{id}', name: 'update_categorie')]
    public function updateCategorie(Categories $categorie, CategoriesRepository $categoriesRepository, Request $request)
    {
        $form = $this->createForm(EditCategoryFormType::class, $categorie);
        $typCarac = $categorie->getTypeCaracteristique();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('info', 'La catégorie a bien été modifiée');
            $categoriesRepository->add($categorie, true);
            return $this->redirectToRoute('update_categorie', ['id' => $categorie->getId()]);
        }
        return $this->render('admin_categories/modify.html.twig', [
            'form' => $form->createView(),
            'typCaracs' => $typCarac
        ]);
    }


    #[Route('/admin/categories/delete/{id}', name: 'delete_categories')]
    public function delConference(CategoriesRepository $CategoriesRepository, Categories $Categories): Response
    {
        $CategoriesRepository->remove($Categories, true);
        $this->addFlash('info', 'La catégorie a bien été supprimée');
        return $this->redirectToRoute('delete_categories_list');
    }

    #[Route('/admin/categories/delete', name: 'delete_categories_list')]
    public function indexdel(CategoriesRepository $CategoriesRepository, Request $request): Response
    {
        $search = $request->query->get('search', '');
        //pagination
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $CategoriesRepository->getPaginator($offset, $search);
        $nbrePages = ceil(count($paginator) / CategoriesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + CategoriesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CategoriesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;

        return $this->render('admin_categories/deletelist.html.twig', [
            'search' => $search,
            'categories' => $paginator,
            'previous' => $offset - CategoriesRepository::PAGINATOR_PER_PAGE,
            'offset' => CategoriesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }
}
