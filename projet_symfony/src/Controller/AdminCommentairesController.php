<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentairesController extends AbstractController
{
    /**
     * page liste des commentaires
     *
     * @param CommentairesRepository $commentairesRepository
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/commentaires', name: 'app_admin_commentaires')]
    public function index(CommentairesRepository $commentairesRepository, Request $request): Response
    {
        //paginator
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentairesRepository->getPaginator($offset);
        $nbrePages = ceil(count($paginator) / CommentairesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + CommentairesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CommentairesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;

        return $this->render('admin_commentaires/index.html.twig', [
            'commentaires' => $paginator,
            'previous' => $offset - CommentairesRepository::PAGINATOR_PER_PAGE,
            'offset' => CommentairesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }

    /**
     * url de suppression
     */
    #[Route('/admin/commentaires/remove/{id}', name: 'app_admin_commentaires_remove')]
    public function remove(Commentaires $commentaires,CommentairesRepository $commentairesRepository, Request $request): Response
    {
        //on supprime le commentaire de la bdd
        $commentairesRepository->remove($commentaires, true);
        //on ajoute un message flash avec le flag 'info'
        $this->addFlash('info', 'Le commentaire a bien été supprimé');

        //paginator
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentairesRepository->getPaginator($offset);
        $nbrePages = ceil(count($paginator) / CommentairesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + CommentairesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CommentairesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;
        return $this->render('admin_commentaires/index.html.twig', [
            'commentaires' => $paginator,
            'previous' => $offset - CommentairesRepository::PAGINATOR_PER_PAGE,
            'offset' => CommentairesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }

    /**
     * url de validation d'un commentaire
     */
    #[Route('/admin/commentaires/validate/{id}', name: 'app_admin_commentaires_validate')]
    public function validate(Commentaires $commentaires,CommentairesRepository $commentairesRepository, Request $request): Response
    {
        //on définit la valeur de isApprouved à true pour le commentaire courant
        $commentaires->setIsApprouved(true);
        //on met à jour le commentaire en bdd
        $commentairesRepository->add($commentaires, true);
        //on ajoute un message flash avec le flag 'info'
        $this->addFlash('info', 'Le commentaire a bien été publié');
        
        //paginator
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentairesRepository->getPaginator($offset);
        $nbrePages = ceil(count($paginator) / CommentairesRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + CommentairesRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / CommentairesRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;
        return $this->render('admin_commentaires/index.html.twig', [
            'commentaires' => $paginator,
            'previous' => $offset - CommentairesRepository::PAGINATOR_PER_PAGE,
            'offset' => CommentairesRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }

}
