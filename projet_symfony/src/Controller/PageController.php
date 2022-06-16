<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Clients;
use App\Entity\Produit;
use App\Form\ModifyUserFormType;
use App\Form\SigninUserFormType;
use App\Repository\AdminRepository;
use App\Repository\CategoriesRepository;
use App\Repository\ClientsRepository;
use App\Repository\FournisseurRepository;
use App\Repository\ProduitRepository;
use App\Services\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/commande/{idClient}', name: 'app_client_commande')]
    public function clientCommande()
    {
    }
    #[Route('/profil/{id}', name: 'app_client_profil')]
    public function clientDonnees(HttpFoundationRequest $request, Clients $clients, CategoriesRepository $categoriesRepository, AdminRepository $adminRepository, ClientsRepository $clientsRepository, UserPasswordHasherInterface $hashedPwd)
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
            if ($form['telephone']->getData() && $form['telephone']->getData() !== $tel) {
                if (count($clientsRepository->findBy(['telephone' => $form['telephone']->getData()])) > 0) {
                    $this->addFlash('error', 'Le numéro de téléphone est déjà utilisé');
                } else {
                    //si le telephone est unique en bdd
                    $this->addFlash('info', 'Le numéro de téléphone a bien été mis à jour');
                    $clientsRepository->add($clients, true); //on envoie sur la bdd
                }
            }

            //si l'adresse mail est renseignée
            if ($form['adresseMail']->getData() && $form['adresseMail']->getData() !== $mail) {
                if (count($clientsRepository->findBy(['adresseMail' => $form['adresseMail']->getData()])) > 0) {

                    $this->addFlash('error', 'L\'adresse Mail est déjà utilisée');
                } else {
                    // si le mail est unique en bdd
                    $this->addFlash('info', 'L\'adresse mail a bien été mise à jour');
                    $clientsRepository->add($clients, true); //on envoie sur la bdd
                }
            }
            //si le nom est renseigné
            if ($form['nom']->getData() && $form['nom']->getData() !== $nom) {
                $this->addFlash('info', 'Votre nom a bien modifié');
                $clientsRepository->add($clients, true); //on envoie sur la bdd
            }
            //si le prénom est renseigné
            if ($form['prenom']->getData() && $form['prenom']->getData() !== $prenom) {
                $this->addFlash('info', 'Le prénom a bien été modifié');
                $clientsRepository->add($clients, true); //on envoie sur la bdd
            }
            //si l'adresse est renseignée
            if ($form['adresse']->getData() && $form['adresse']->getData() !== $adresse) {
                $this->addFlash('info', 'L\'adresse mail a bien été mise à jour');
                $clientsRepository->add($clients, true); //on envoie sur la bdd
            }
            //si le code postal est renseigné
            if ($form['codePostale']->getData() && $form['codePostale']->getData() !== $cp) {
                $this->addFlash('info', 'Le code postal a bien été mis à jour');
                $clientsRepository->add($clients, true); //on envoie sur la bdd
            }
            //si la ville est renseignée
            if ($form['ville']->getData() && $form['ville']->getData() !== $ville) {
                $this->addFlash('info', 'La ville a bien été mise à jour');
                $clientsRepository->add($clients, true); //on envoie sur la bdd
            }

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
    public function categoriesCatalogue(HttpFoundationRequest $request, $idCat, CategoriesRepository $categoriesRepository, ProduitRepository $produitRepository, string $ProduitDir): Response
    {   //pour l'affichage du menu
        $getCategories = self::Menu($categoriesRepository);
        //on récupère la catégorie courante
        $categorie = $categoriesRepository->findBy(['id' => $idCat]);
        //on créée les variables de la pagination
        $offset = max(0, $request->query->getInt('offset', 0));
        $produits = $produitRepository->getPaginatorFront($offset, $categorie[0]->getId());
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

        ]);
    }
    #[Route('/produits/{id}', name: 'app_categories_produits')]
    public function categoriesProduit(Panier $panier,HttpFoundationRequest $request,Produit $produit, CategoriesRepository $categoriesRepository, ProduitRepository $produitRepository, string $ProduitDir): Response
    {   //pour l'affichage du menu
        $getCategories = self::Menu($categoriesRepository);
        //on récupère les produits
        $groupProduit = null;
        if($request->query->get('id') && $request->query->get('quantite')){
            $panier->modifPanier($request->query->get('id'), $request->query->get('quantite'));
        }
        if ($produit->getGroupProduit()) {
            $groupProduit = $produitRepository->findBy(['groupProduit' => $produit->getGroupProduit(), 'is_active' => true]);
        }
        return $this->render('front/page/produit.html.twig', [
            'categories' => $getCategories,
            'produits' => $produit,
            'dir' => $ProduitDir,
            'groupProduits' => $groupProduit

        ]);
    }

    static public function Menu(CategoriesRepository $categoriesRepository)
    {
        return $categoriesRepository->findAll();
    }

    /**
     * fonction pagination à revoir
     *
     * @param [type] $paginator_per_page
     * @param [type] $repositoryString
     * @param [type] $paginator
     * @param [type] $offset
     * @return void
     */
    static public function Pagination($paginator_per_page, $repositoryString, $paginator, $offset)
    {
        $nbrePages = ceil(count($paginator) / $repositoryString::$paginator_per_page);
        $next = min(count($paginator), $offset + $repositoryString::$paginator_per_page);
        $pageActuelle = ceil($next / $repositoryString::$paginator_per_page);
        $difPages = $nbrePages - $pageActuelle;

        return [$nbrePages, $next, $pageActuelle, $difPages];
    }
}
