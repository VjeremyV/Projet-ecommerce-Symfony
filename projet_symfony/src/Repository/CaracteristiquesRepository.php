<?php

namespace App\Repository;

use App\Entity\Caracteristiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Caracteristiques>
 *
 * @method Caracteristiques|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caracteristiques|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caracteristiques[]    findAll()
 * @method Caracteristiques[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaracteristiquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caracteristiques::class);
    }

    public function add(Caracteristiques $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Caracteristiques $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public const PAGINATOR_PER_PAGE = 15;
    public function getCaracteristiquesPaginator(int $offset, $searchNom, array $options = null): Paginator
    {
        $query = $this->createQueryBuilder('c');
        if ($searchNom) {
            $query = $query->Where('c.nom LIKE :nom')
                ->setParameter('nom', '%' . $searchNom . '%');
        }
        if (isset($options['nom_search'])) {
            $query = $query
                ->addOrderBy('c.nom');
        }
        if (isset($options['type_Caracteristiques_search'])) {
            $query = $query
                ->join('c.typeCaracteristiques', 'typeCaracteristiques')
                ->addOrderBy('typeCaracteristiques.id');
        }
        $query = $query
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }

    public function getListCaracteristique(): array
    {
        $nom = [];
        foreach ($this->createQueryBuilder('c')
            ->select('c.nom')
            ->distinct(true)
            ->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult() as $cols) {
            $nom[] = $cols['nom'];
        }
        return $nom;
    }

    //    /**
    //     * @return Caracteristiques[] Returns an array of Caracteristiques objects
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

    //    public function findOneBySomeField($value): ?Caracteristiques
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
