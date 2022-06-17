<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Clients;
use App\Entity\Commandes;
use App\Entity\Produit;
use App\Services\Panier;
use App\Entity\Commentaires;
use App\Form\ModifyUserFormType;
use App\Form\SigninUserFormType;
use App\Repository\AdminRepository;
use App\Form\AddCommentaireFormType;
use App\Repository\CaracteristiquesRepository;
use App\Repository\ClientsRepository;
use App\Repository\CommandesRepository;
use App\Repository\ContenuRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategoriesRepository;
use App\Repository\CommentairesRepository;
use App\Repository\FournisseurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\InfosUtilisateur;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\IsFalse;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ProduitRepository $produitRepository, CategoriesRepository $categoriesRepository, string $ProduitDir, FournisseurRepository $fournisseurRepository, string $DirFour): Response
    {
        $fournisseurs = $fournisseurRepository->findAll();
        $produits = $produitRepository->findBy(['groupProduit' => 'PDM', 'is_active' => true]);
        $getCategories = self::Menu($categoriesRepository);
        return $this->render('front/page/index.html.twig', [
            'categories' => $getCategories,
            'produits' => $produits,
            'dir' => $ProduitDir,
            'fournisseurs' => $fournisseurs,
            'fourdir' => $DirFour
        ]);
    }

    #[Route('/commande', name: 'app_client_commande')]
    public function clientCommande(CategoriesRepository $categoriesRepository,CommandesRepository $commandesRepository, ClientsRepository $clientsRepository,ContenuRepository $contenuRepository){
        $getCategories = self::Menu($categoriesRepository);
        $getCommande = $commandesRepository->findAll();
        $getClient = $clientsRepository->findAll();
        return $this->render('client_commande/index.html.twig', [
            'categories'=> $getCategories,
            'commandes'=>$getCommande,
            'client'=> $getClient,
        ]);
    }
    #[Route('/commande{id}', name: 'app_commande_view')]
    public function CommandePage(CategoriesRepository $categoriesRepository,CommandesRepository $commandesRepository, ClientsRepository $clientsRepository,ContenuRepository $contenuRepository,Commandes $commandes){
        $getCategories = self::Menu($categoriesRepository);
        $getCommande = $commandesRepository->findAll();
        $getClient = $clientsRepository->findAll();
        $contenu = $contenuRepository->findBy(['commandes'=>$commandes->getId()]);
        return $this->render('client_commande/view.html.twig', [
            'categories'=> $getCategories,
            'commandes'=>$getCommande,
            'client'=> $getClient,
            'contenu'=>$contenu,
        ]);
    }

    #[Route('/profil/{id}', name: 'app_client_profil')]
    public function clientDonnees(HttpFoundationRequest $request, Clients $clients, CategoriesRepository $categoriesRepository, AdminRepository $adminRepository, ClientsRepository $clientsRepository, UserPasswordHasherInterface $hashedPwd, InfosUtilisateur $infosUtilisateur)
    {

        $form = $this->createForm(ModifyUserFormType::class, $clients);
        $tel = $clients->getTelephone();
        $mail = $clients->getAdresseMail();
        $nom = $clients->getNom();
        $prenom = $clients->getPrenom();
        $adresse = $clients->getAdresse();
        $cp = $clients->getCodePostale();
        $ville = $clients->getVille();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $adm = $clients->getAdmin();
            //si le pseudo est renseigné


            if ($form['pseudo']->getData() && $adm->getPseudo() !== $form['pseudo']->getData()) {
                if (!$adminRepository->findBy(['pseudo' => $form['pseudo']->getData()])) {
                    //si le pseudo est unique en bdd
                    $this->addFlash('info', 'Votre pseudo a bien été modifié');
                    $adm->setPseudo($form['pseudo']->getData());
                    $adminRepository->add($adm, true);
                } else {
                    $this->addFlash('error', 'Le pseudo est déjà utilisé');
                }
            }
            //si le telephone est renseigné
            $infosUtilisateur->checkTelephone($form, $clients, $tel);
            //si l'adresse mail est renseignée
            $infosUtilisateur->checkMail($form, $clients, $mail);
            //si le nom est renseigné
            $infosUtilisateur->checkNom($form, $clients, $nom);
            //si le prénom est renseigné
            //si l'adresse est renseignée
            $infosUtilisateur->checkAdresse($form, $clients, $adresse);
            $infosUtilisateur->checkPrenom($form, $clients, $prenom);
            //si le code postal est renseigné
            $infosUtilisateur->checkCp($form, $clients, $cp);
            //si la ville est renseignée
            $infosUtilisateur->checkVille($form, $clients, $ville);

            //si il y a un mot de pass
            if ($form['MDP']->getData()) {
                if ($form['MDP']->getData() === $form['Confirmmdp']->getData()) {
                    //si les 2 mot de passe rentrés sont égaux
                    $exp = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/'; // on prépare une regex
                    if (preg_match($exp, $form['MDP']->getData())) {
                        //si le mdp correspond à la regex
                        $this->addFlash('info', 'Votre inscription s\'est bien passée'); // on ajoute un message de reussite
                        $pwd = $hashedPwd->hashPassword($adm, $form['MDP']->getData()); //on hash le mdp
                        $adm->setPassword($pwd); //on définit les valeurs de Admin avec les champs du formulaire
                        $clients->setAdmin($adm); // on définit le champs Admin de user avec notre objet admin

                        $clientsRepository->add($clients, true); //on envoie sur la bdd
                        return $this->redirectToRoute('app_signin'); // on redirige sur la même page
                    } else {
                        $this->addFlash('error', 'Le mot de passe doit contenir au moins 10 caractères, 1minuscule, 1 majuscule, 1caractère spécial et 1 chiffre');
                    }
                } else {
                    $this->addFlash('error', 'Vos 2 mots de passe ne correspondent pas');
                }
            }
        }
        $getCategories = self::Menu($categoriesRepository);
        return $this->render('front/page/profil.html.twig', [
            'categories' => $getCategories,
            'form' => $form->createView()
        ]);
    }





    #[Route('/categories/{idCat}', name: 'app_categories_catalogue')]
    public function categoriesCatalogue(HttpFoundationRequest $request, $idCat, CategoriesRepository $categoriesRepository, ProduitRepository $produitRepository, string $ProduitDir, CaracteristiquesRepository $caracteristiquesRepository): Response
    {   //pour l'affichage du menu
        $getCategories = self::Menu($categoriesRepository);
        //on récupère la catégorie courante
        $categorie = $categoriesRepository->findBy(['id' => $idCat]);
        //on créée les variables de la pagination
        $options = ['prix' => $request->query->get('prix')];
        $typeCaracs = $categorie[0]->getTypeCaracteristique();
        foreach ($typeCaracs as $typeCarac) {
            if (isset($_GET[$typeCarac->getId()])) {
                $typCarac = $_GET[$typeCarac->getId()];
                foreach($typCarac as $carac){
                    $options['caracs'][] = $carac;
                }
            }
        }
        $offset = max(0, $request->query->getInt('offset', 0));
        $produits = $produitRepository->getPaginatorFront($offset, $categorie[0]->getId(), $options);
        $nbrePages = ceil(count($produits) / ProduitRepository::PAGINATOR_PER_PAGE_FRONT);
        $next = min(count($produits), $offset + ProduitRepository::PAGINATOR_PER_PAGE_FRONT);
        $pageActuelle = ceil($next / ProduitRepository::PAGINATOR_PER_PAGE_FRONT);
        $difPages = $nbrePages - $pageActuelle;
        return $this->render('front/page/categorie_catalogue.html.twig', [
            'categories' => $getCategories,
            'categorie' =>  $categorie,
            'produits' => $produits,
            'dir' => $ProduitDir,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE_FRONT,
            'offset' => ProduitRepository::PAGINATOR_PER_PAGE_FRONT,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
            'typeCaracs' => $typeCaracs,
            'options' => $options

        ]);
    }
    #[Route('/produits/{id}', name: 'app_categories_produits')]
    public function categoriesProduit(Panier $panier, HttpFoundationRequest $request, Produit $produit, CategoriesRepository $categoriesRepository, ProduitRepository $produitRepository, string $ProduitDir, CommentairesRepository $commentairesRepository, ClientsRepository $clientsRepository): Response
    //pour l'affichage du menu
    {   //pour l'affichage du menu
        $getCategories = self::Menu($categoriesRepository);
    
        //Si on a une query string 'id' et une autre 'quantite
        if ($request->query->get('id') && $request->query->get('quantite')) {
            //on modifie le panier
            $panier->modifPanier($request->query->get('id'), $request->query->get('quantite'));
        }

        //on définit une variable null
        $groupProduit = null;
        //Si le produit est affilié à un groupe de produit
        if ($produit->getGroupProduit()) {
            //on récupère les produits associés dans la variable précédement créée
            $groupProduit = $produitRepository->findBy(['groupProduit' => $produit->getGroupProduit(), 'is_active' => true]);
        }
        //on initialise un nouveau commentaire
        $comment = new Commentaires();
        //on se créée un formulaire pour saisir nos commentaires
        $form = $this->createForm(AddCommentaireFormType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $client = $clientsRepository->find($form['auteur']->getData());
            $comment->setAuteur($client);
            $date = new DateTimeImmutable();
            $comment->setCreatedAt($date);
            $comment->setProduit($produit);
            $comment->setAuteur($client);
            $comment->setIsApprouved(false);
            $commentairesRepository->add($comment, true);
            $this->addFlash('info', 'Votre commentaire a bien été soumis, il est actuellement en cours de modération');
            return $this->redirectToRoute('app_categories_produits', ['id' => $produit->getId()]);
        }
        //On récupère les commentaire du produit courant 
        $comments = $commentairesRepository->findBy(['produit' => $produit, 'isApprouved' => 1]);
        return $this->render('front/page/produit.html.twig', [
            'categories' => $getCategories,
            'produits' => $produit,
            'dir' => $ProduitDir,
            'groupProduits' => $groupProduit,
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }

    static public function Menu(CategoriesRepository $categoriesRepository)
    {
        return $categoriesRepository->findAll();
    }

}
