<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategorieAddFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    #[Route('/admin/categories/add', name: 'app_admin_categories_add')]
    public function AddCategorie(): Response
    {
        $categorie = new Categories();
        $form = $this->createForm(CategorieAddFormType::class, $categorie);
        return $this->render('admin_categories/index.html.twig', [
        'form' => $form
        ]);
    }
}
