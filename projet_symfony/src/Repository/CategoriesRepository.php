<?php

namespace App\Repository;

use App\Entity\Categories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categories>
 *
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categories::class);
    }

    public function add(Categories $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Categories $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

      /**
     * @return array Returns an array of categories objects
     */
    public function getListCategories(): array
    {
        $nom =[];
        foreach ($this->createQueryBuilder('t')
            ->select('t.nom')
            ->orderBy('t.nom', 'ASC')
            ->getQuery()
            ->getResult() as $cols){
            $nom[] = $cols['nom'];
    }
        return $nom;
    }

    public const PAGINATOR_PER_PAGE = 4;
    public function getPaginator( int $offset, $searchNom): Paginator
    {
        $query = $this->createQueryBuilder('t');
        if ($searchNom){
            $query =$query->Where('t.nom LIKE :nom')
                ->setParameter('nom','%'.$searchNom.'%');
        }
        $query = $query
            ->OrderBy('t.nom')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }

//    /**
//     * @return Categories[] Returns an array of Categories objects
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

//    public function findOneBySomeField($value): ?Categories
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
