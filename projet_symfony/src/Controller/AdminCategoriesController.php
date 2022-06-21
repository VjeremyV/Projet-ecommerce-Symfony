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
    
    /**
     * page d'ajoute d'une catégorie
     *
     * @param Request $request
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route('/admin/categories/add', name: 'app_admin_categories_add')]
    public function AddCategorie(Request $request, CategoriesRepository $categoriesRepository): Response
    {
        // on instancie une nouvelle catégorie
        $categorie = new Categories();
        //on crée le formulaire de création
        $form = $this->createForm(CategorieAddFormType::class, $categorie);

        //si il est soumis on récupère les données de la requête
        $form->handleRequest($request);
        //si il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'La catégorie a bien été ajoutée');
            //on ajoute la catégorie à la bdd
            $categoriesRepository->add($categorie, true);
            //on redirige vers la liste des catégories
            return $this->redirectToRoute('categories_update_list');
        }
        return $this->render('admin_categories/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * page de la liste des categories
     *
     * @param CategoriesRepository $categoriesRepository
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categories/update', name: 'categories_update_list')]
    public function indexUp(CategoriesRepository $categoriesRepository, Request $request)
    {
        //on récupère la query string 'search' qui correspond à une recherche dans la barre de recherche
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

    /**
     * page d'edition d'une catégorie
     */
    #[Route('/admin/categories/update/{id}', name: 'update_categorie')]
    public function updateCategorie(Categories $categorie, CategoriesRepository $categoriesRepository, Request $request)
    {
        //on crée le formulaire d'édition
        $form = $this->createForm(EditCategoryFormType::class, $categorie);
        //on récupère les types de caractéristiques liés à la catégorie
        $typCarac = $categorie->getTypeCaracteristique();
        //si il est soumis on récupère les données de la requête
        $form->handleRequest($request);
        //si il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'La catégorie a bien été modifiée');
            //on met à jour la catégorie en bdd
            $categoriesRepository->add($categorie, true);
            //on redirige vers la même page
            return $this->redirectToRoute('update_categorie', ['id' => $categorie->getId()]);
        }
        return $this->render('admin_categories/modify.html.twig', [
            'form' => $form->createView(),
            'typCaracs' => $typCarac
        ]);
    }

/**
 * url de suppression de categorie
 */
    #[Route('/admin/categories/delete/{id}', name: 'delete_categories')]
    public function delConference(CategoriesRepository $CategoriesRepository, Categories $Categories): Response
    {
        //on supprime la catégorie courante
        $CategoriesRepository->remove($Categories, true);
        //on ajoute un message flash avec le flag 'info'
        $this->addFlash('info', 'La catégorie a bien été supprimée');
        //on redirige vers la liste des catégories
        return $this->redirectToRoute('categories_update_list');
    }

}
