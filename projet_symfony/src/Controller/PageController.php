<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Commandes;
use App\Entity\Produit;
use App\Services\Panier;
use App\Entity\Commentaires;
use App\Form\ModifyUserFormType;
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
use App\Repository\GroupProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\InfosUtilisateur;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PageController extends AbstractController
{
    /**
     * page d'accueil
     *
     * @param CategoriesRepository $categoriesRepository
     * @param string $ProduitDir
     * @param FournisseurRepository $fournisseurRepository
     * @param string $DirFour
     * @param GroupProduitRepository $groupProduitRepository
     * @return Response
     */
    #[Route('/', name: 'app_accueil')]
    public function index(CategoriesRepository $categoriesRepository, string $ProduitDir, FournisseurRepository $fournisseurRepository, string $DirFour, GroupProduitRepository $groupProduitRepository): Response
    {
        //on récupère le groupe produit correspondant à la mise en avant sur la page d'accueil
        $groupAccueil = $groupProduitRepository->find('1');
        //on récupère les produits à mettre en avant
        $gProduits = $groupAccueil->getProduits();
        //on créée un tableau produit vide
        $produits = [];
        foreach ($gProduits as $produit) {
            //pour chaque produit mis en avant on vérifie que le produit est actif et on l'ajoute ensuite dans notre tableau produit
            if ($produit->isIsActive()) {
                $produits[] = $produit;
            }
        }
        //on récupère tous nos fournisseurs
        $fournisseurs = $fournisseurRepository->findAll();
        //on récupère les catégories pour les afficher dans le menu
        $getCategories = self::Menu($categoriesRepository);
        return $this->render('front/page/index.html.twig', [
            'categories' => $getCategories,
            'produits' => $produits,
            'dir' => $ProduitDir,
            'fournisseurs' => $fournisseurs,
            'fourdir' => $DirFour
        ]);
    }


    /**
     * Page listing des commandes passées
     *
     * @param Request $request
     * @param CategoriesRepository $categoriesRepository
     * @param CommandesRepository $commandesRepository
     * @param ClientsRepository $clientsRepository
     * @param ContenuRepository $contenuRepository
     * @return void
     */
    #[Route('/commande', name: 'app_client_commande')]
    public function clientCommande(Request $request, CategoriesRepository $categoriesRepository, CommandesRepository $commandesRepository, ClientsRepository $clientsRepository, ContenuRepository $contenuRepository)
    {
        //on récupère les catégories pour les afficher dans le menu
        $getCategories = self::Menu($categoriesRepository);

        //pagination
        $offset = max(0, $request->query->getInt('offset', 0));
        $getCommande = $commandesRepository->getPaginatorCommande($offset);
        $getClient = $clientsRepository->findAll();
        $produits = $commandesRepository->getPaginatorCommande($offset);
        $nbrePages = ceil(count($produits) / CommandesRepository::PAGINATOR_PER_PAGE_COMMANDE);
        $next = min(count($produits), $offset + CommandesRepository::PAGINATOR_PER_PAGE_COMMANDE);
        $pageActuelle = ceil($next / CommandesRepository::PAGINATOR_PER_PAGE_COMMANDE);
        $difPages = $nbrePages - $pageActuelle;
        return $this->render('client_commande/index.html.twig', [
            'categories' => $getCategories,
            'commandes' => $getCommande,
            'client' => $getClient,
            'previous' => $offset - CommandesRepository::PAGINATOR_PER_PAGE_COMMANDE,
            'offset' => CommandesRepository::PAGINATOR_PER_PAGE_COMMANDE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages
        ]);
    }


    /**
     * page detail d'une commande
     *
     * @param CategoriesRepository $categoriesRepository
     * @param CommandesRepository $commandesRepository
     * @param ClientsRepository $clientsRepository
     * @param ContenuRepository $contenuRepository
     * @param Commandes $commandes
     * @return void
     */
    #[Route('/commande{id}', name: 'app_commande_view')]
    public function CommandePage(CategoriesRepository $categoriesRepository, CommandesRepository $commandesRepository, ClientsRepository $clientsRepository, ContenuRepository $contenuRepository, Commandes $commandes)
    {
        //on récupère les catégories pour les afficher dans le menu
        $getCategories = self::Menu($categoriesRepository);
        //on récupère le contenu de la commande
        $contenu = $contenuRepository->findBy(['commandes' => $commandes->getId()]);
        return $this->render('client_commande/view.html.twig', [
            'categories' => $getCategories,
            'contenu' => $contenu,
        ]);
    }


    /**
     * Page profil
     *
     * @param HttpFoundationRequest $request
     * @param Clients $clients
     * @param CategoriesRepository $categoriesRepository
     * @param AdminRepository $adminRepository
     * @param ClientsRepository $clientsRepository
     * @param UserPasswordHasherInterface $hashedPwd
     * @param InfosUtilisateur $infosUtilisateur
     * @return void
     */
    #[Route('/profil/{id}', name: 'app_client_profil')]
    public function clientDonnees(HttpFoundationRequest $request, Clients $clients, CategoriesRepository $categoriesRepository, AdminRepository $adminRepository, ClientsRepository $clientsRepository, UserPasswordHasherInterface $hashedPwd, InfosUtilisateur $infosUtilisateur)
    {
        //on créée le formulaire de modification
        $form = $this->createForm(ModifyUserFormType::class, $clients);
        //on récupère toutes les données du client avant soumission du formulaire
        $tel = $clients->getTelephone();
        $mail = $clients->getAdresseMail();
        $nom = $clients->getNom();
        $prenom = $clients->getPrenom();
        $adresse = $clients->getAdresse();
        $cp = $clients->getCodePostale();
        $ville = $clients->getVille();

        //si soumis on récupère la request
        $form->handleRequest($request);
        //si soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //on créée une variable dans laquelle on stock la valeur admin de notre client
            $adm = $clients->getAdmin();

            //si le pseudo est renseigné
            if ($form['pseudo']->getData() && $adm->getPseudo() !== $form['pseudo']->getData()) {
                //si le pseudo est unique en bdd
                if (!$adminRepository->findBy(['pseudo' => $form['pseudo']->getData()])) {
                    //on ajoute un message flash avec le flag 'info'
                    $this->addFlash('info', 'Votre pseudo a bien été modifié');
                    //on met à jour le pseudo dans $adm
                    $adm->setPseudo($form['pseudo']->getData());
                    //on met à jour la bdd
                    $adminRepository->add($adm, true);
                } else {
                    //si une erreur alors message flash d'erreur avec le flag 'error'
                    $this->addFlash('error', 'Le pseudo est déjà utilisé');
                }
            }

            //VOIR LES METHODES DANS LE SERVICE InfosUtilisateur

            //si le telephone est renseigné
            $infosUtilisateur->checkTelephone($form, $clients, $tel);
            //si l'adresse mail est renseignée
            $infosUtilisateur->checkMail($form, $clients, $mail);
            //si le nom est renseigné
            $infosUtilisateur->checkNom($form, $clients, $nom);
            //si l'adresse est renseignée
            $infosUtilisateur->checkAdresse($form, $clients, $adresse);
            //si le prénom est renseigné
            $infosUtilisateur->checkPrenom($form, $clients, $prenom);
            //si le code postal est renseigné
            $infosUtilisateur->checkCp($form, $clients, $cp);
            //si la ville est renseignée
            $infosUtilisateur->checkVille($form, $clients, $ville);

            //si il y a un mot de passe
            if ($form['MDP']->getData()) {
                //si les 2 mot de passe rentrés sont égaux
                if ($form['MDP']->getData() === $form['Confirmmdp']->getData()) {
                    // on prépare une regex
                    $exp = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/';
                    //si le mdp correspond à la regex
                    if (preg_match($exp, $form['MDP']->getData())) {
                        // on ajoute un message de reussite
                        $this->addFlash('info', 'Votre inscription s\'est bien passée');
                        //on hash le mdp
                        $pwd = $hashedPwd->hashPassword($adm, $form['MDP']->getData());
                        //on définit les valeurs de Admin avec les champs du formulaire
                        $adm->setPassword($pwd);
                        // on définit le champs Admin de user avec notre objet admin
                        $clients->setAdmin($adm);
                        //on envoie sur la bdd
                        $clientsRepository->add($clients, true);
                        // on redirige sur la même page
                        return $this->redirectToRoute('app_signin'); 
                    } else {
                        //si une erreur alors message flash d'erreur avec le flag 'error'
                        $this->addFlash('error', 'Le mot de passe doit contenir au moins 10 caractères, 1minuscule, 1 majuscule, 1caractère spécial et 1 chiffre');
                    }
                } else {
                    //si une erreur alors message flash d'erreur avec le flag 'error'
                    $this->addFlash('error', 'Vos 2 mots de passe ne correspondent pas');
                }
            }
        }
        //on récupère les catégories pour les afficher dans le menu
        $getCategories = self::Menu($categoriesRepository);

        return $this->render('front/page/profil.html.twig', [
            'categories' => $getCategories,
            'form' => $form->createView()
        ]);
    }





    /**
     * page catalogue d'une catégorie
     *
     * @param HttpFoundationRequest $request
     * @param [type] $idCat
     * @param CategoriesRepository $categoriesRepository
     * @param ProduitRepository $produitRepository
     * @param string $ProduitDir
     * @param CaracteristiquesRepository $caracteristiquesRepository
     * @return Response
     */
    #[Route('/categories/{idCat}', name: 'app_categories_catalogue')]
    public function categoriesCatalogue(HttpFoundationRequest $request, $idCat, CategoriesRepository $categoriesRepository, ProduitRepository $produitRepository, string $ProduitDir, CaracteristiquesRepository $caracteristiquesRepository): Response
    {   //on récupère les catégories pour les afficher dans le menu
        $getCategories = self::Menu($categoriesRepository);

        //on récupère la catégorie courante
        $categorie = $categoriesRepository->findBy(['id' => $idCat]);

        //on créée les variables de la pagination par rapport aux filtres
        //on aura une premiere variable correspondant à l'organisation par rapport au prix
        $options = ['prix' => $request->query->get('prix')];
        //on récupère les types de caracteristiques liés à la categorie courante
        $typeCaracs = $categorie[0]->getTypeCaracteristique();
        //pour chaque type de caracteristique 
        foreach ($typeCaracs as $typeCarac) {
            //on vérifie si il exite une variable get correspondant à l'id 
            if (isset($_GET[$typeCarac->getId()])) {
                //on stock dans une variable l'id du type de caracteristique
                $typCarac = $_GET[$typeCarac->getId()];
                //pour chaque caracteristique du type de caracteristiques
                foreach ($typCarac as $carac) {
                    //on ajoute dans notre tableau d'option à l'indice 'caracs', la caracteristique
                    $options['caracs'][] = $carac;
                }
            }
        }

        //paginator
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


    /**
     * page détail d'un produit
     *
     * @param [type] $id
     * @param Panier $panier
     * @param HttpFoundationRequest $request
     * @param Produit $produit
     * @param CategoriesRepository $categoriesRepository
     * @param ProduitRepository $produitRepository
     * @param string $ProduitDir
     * @param CommentairesRepository $commentairesRepository
     * @param ClientsRepository $clientsRepository
     * @param CaracteristiquesRepository $caracteristiquesRepository
     * @return Response
     */
    #[Route('/produits/{id}', name: 'app_categories_produits')]
    public function categoriesProduit($id, Panier $panier, HttpFoundationRequest $request, Produit $produit, CategoriesRepository $categoriesRepository, ProduitRepository $produitRepository, string $ProduitDir, CommentairesRepository $commentairesRepository, ClientsRepository $clientsRepository, CaracteristiquesRepository $caracteristiquesRepository): Response
    {   //on récupère les catégories pour les afficher dans le menu
        $getCategories = self::Menu($categoriesRepository);
        // on récupère les caractéristiques liés au produit
        $caracteristique = $produit->getCaracteristiques();

        //Si on a une query string 'id' et une autre 'quantite
        if ($request->query->get('id') && $request->query->get('quantite')) {
            //si le stock est supérieur ou égal à la quantité saisie
            if ($produit->getStock() >= $request->query->get('quantite')) {
                //on modifie le panier, voir service Panier pour la méthode
                $panier->modifPanier($request->query->get('id'), $request->query->get('quantite'));
            } else {
                //on créée un message flash d'erreur avec le flag 'error'
                $this->addFlash('error', 'Attention, il ne reste plus que ' . $produit->getStock() . ' unités en stock');
            }
        }

        //on définit un tableau vide
        $groupProduit = [];
        //Si le produit est affilié à un groupe de produit
        if (count($produit->getGroupProduit()) > 0) {
            //on récupère les produits associés dans le tableau précédement créé
            foreach ($produit->getGroupProduit() as $group) {
                $groupProduit[] = $produitRepository->produitParGroup($group);
            }
        }
        //on initialise un nouveau commentaire
        $comment = new Commentaires();
        //on se créée un formulaire pour saisir nos commentaires
        $form = $this->createForm(AddCommentaireFormType::class, $comment);

        //si formulaire soumis on récupère la request
        $form->handleRequest($request);
        //si soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            //on récupère l'utilisateur courant
            $client = $clientsRepository->find($form['auteur']->getData());
            //on définit l'utilisateur comme étant l'auteur
            $comment->setAuteur($client);
            //on récupère la date courante
            $date = new DateTimeImmutable();
            //on définit la date de cration avec la date courante
            $comment->setCreatedAt($date);
            //on définit le produit associé au commentaire
            $comment->setProduit($produit);
            //on met par défaut le commentaire en non approuvé pour qu'il soit modéré
            $comment->setIsApprouved(false);
            //on envoie le commentaire en bdd
            $commentairesRepository->add($comment, true);
            //on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'Votre commentaire a bien été soumis, il est actuellement en cours de modération');
            // on redirige vers la même page
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
            'comments' => $comments,
            'caracteristiques' => $caracteristique
        ]);
    }

    /**
     * récupère les catégories existantes
     *
     * @param CategoriesRepository $categoriesRepository
     * @return void
     */
    static public function Menu(CategoriesRepository $categoriesRepository)
    {
        return $categoriesRepository->findAll();
    }
}
