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
    #[Route('/admin/commentaires', name: 'app_admin_commentaires')]
    public function index(CommentairesRepository $commentairesRepository, Request $request): Response
    {
        $options=[];
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentairesRepository->getPaginator($offset, $options);
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
    #[Route('/admin/commentaires/remove', name: 'app_admin_commentaires_remove')]
    public function remove(CommentairesRepository $commentairesRepository, Request $request): Response
    {
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

    #[Route('/admin/commentaires/validate/{id}', name: 'app_admin_commentaires_validate')]
    public function validate(Commentaires $commentaires,CommentairesRepository $commentairesRepository, Request $request): Response
    {
        $commentaires->setIsApprouved(true);
        $commentairesRepository->add($commentaires, true);

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
