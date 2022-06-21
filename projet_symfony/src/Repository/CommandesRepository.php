<?php

namespace App\Repository;

use App\Entity\Commandes;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Commandes>
 *
 * @method Commandes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commandes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commandes[]    findAll()
 * @method Commandes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commandes::class);
    }

    public function add(Commandes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commandes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public const PAGINATOR_PER_PAGE = 4;
    /**
     * paginator coté backoffice
     *
     * @param integer $offset
     * @param [type] $search
     * @param [type] $options
     * @return Paginator
     */
    public function getPaginator(int $offset, $search = null, $options = null): Paginator
    {
        $query = $this->createQueryBuilder('c');
        if ($search) {
            $query = $query->join('c.Client', 'client')
            ->Where('client.nom LIKE :nom')
                ->setParameter('nom', '%' . $search . '%');
        }
        if (isset($options['nom_search'])) {
            $query = $query->join('c.Client', 'client')
                ->addOrderBy('client.nom');
        }
        if (isset($options['date_search'])) {
            $query = $query
                ->addOrderBy('c.createdAt', 'DESC');
        }
        if (isset($options['id_search'])) {
            $query = $query
                ->addOrderBy('c.id');
        }
        if (isset($options['montant_search'])) {
            $query = $query
                ->addOrderBy('c.Montant');
        }
        $query = $query
            ->addOrderBy('c.id', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }

    public const PAGINATOR_PER_PAGE_COMMANDE = 5;
    /**
     * paginator coté client
     *
     * @param integer $offset
     * @return Paginator
     */
    public function getPaginatorCommande(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('c');
        $query = $query->addOrderBy('c.createdAt','DESC');
        $query = $query
            ->setMaxResults(self::PAGINATOR_PER_PAGE_COMMANDE)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }

    //    /**
    //     * @return Commandes[] Returns an array of Commandes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commandes
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
