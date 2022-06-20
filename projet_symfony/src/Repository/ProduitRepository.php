<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function add(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public const PAGINATOR_PER_PAGE = 15;
    public function getPaginator(int $offset, $searchNom, array $options = null): Paginator
    {
        $query = $this->createQueryBuilder('t');
        if ($searchNom) {
            $query = $query->Where('t.nom LIKE :nom')
                ->setParameter('nom', '%' . $searchNom . '%');
        }
        if (isset($options['nom_search'])) {
            $query = $query
                ->addOrderBy('t.nom');
        }
        if (isset($options['categorie_search'])) {
            $query = $query
                ->join('t.categorie', 'Categories')
                ->OrderBy('Categories.id');
        }
        if (isset($options['four_search'])) {
            $query = $query
                ->join('t.fournisseur', 'Fournisseur')
                ->addOrderBy('Fournisseur.id');
        }
        if (isset($options['actif_search'])) {
            $query = $query
                ->addOrderBy('t.is_active');
        }
        $query = $query
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }

    public const PAGINATOR_PER_PAGE_FRONT = 12;
    public function getPaginatorFront(int $offset, $categorie, $options = null): Paginator
    {
        $query = $this->createQueryBuilder('t');
        $query = $query
            ->Where('t.categorie = :cat')
            ->setParameter('cat', $categorie)
            ->AndWhere('t.is_active = 1')
            ->orderBy('t.prix');
        if($options){
            if($options['prix'] === 'prixD'){
                $query = $query->orderBy('t.prix', 'DESC');
            }
            if(isset($options['caracs'])){
                $query = $query->join('t.caracteristiques', 'c')
                ->AndWhere('c.id IN (:id)')
                ->setParameter('id', $options['caracs']);
            }
        }

        $query = $query->setMaxResults(self::PAGINATOR_PER_PAGE_FRONT)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }



















    public function getPaginatorSearch(int $offset, $search): Paginator
    {
        $query = $this->createQueryBuilder('t');
        $query = $query
            ->Where('t.nom LIKE :nom')
            ->setParameter('nom', '%'.$search. '%')
            ->AndWhere('t.is_active = 1')
            ->orderBy('t.prix')
            ->setMaxResults(self::PAGINATOR_PER_PAGE_FRONT)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }

       public function produitParGroup($array): array
       {
           $query =  $this->createQueryBuilder('p')
           ->andWhere('p.is_active = 1')
           ->join('p.GroupProduit' , 'g');

            foreach($array as $element){
                $query = $query->andWhere('g = :val')
                ->setParameter('val' , $element);
            }

               $query = $query->getQuery()
               ->getResult()
           ;
           return $query;
       }

    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
