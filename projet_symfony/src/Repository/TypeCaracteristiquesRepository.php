<?php

namespace App\Repository;

use App\Entity\TypeCaracteristiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeCaracteristiques>
 *
 * @method TypeCaracteristiques|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCaracteristiques|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCaracteristiques[]    findAll()
 * @method TypeCaracteristiques[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCaracteristiquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCaracteristiques::class);
    }

    public function add(TypeCaracteristiques $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeCaracteristiques $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public const PAGINATOR_PER_PAGE = 10;
    public function getTypeCaracteristiquesPaginator(int $offset, $searchNom): Paginator
    {
        $query = $this->createQueryBuilder('t');
        if ($searchNom) {
            $query = $query->Where('t.nom LIKE :nom')
                ->setParameter('nom', '%' . $searchNom . '%');
        }
        $query = $query
            ->OrderBy('t.nom')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }

    /**
     * @return TypeCaracteristiques[] Returns an array of TypeCaracteristiques objects
     */
    public function getListTypeCaracteristique(): array
    {
        $nom = [];
        foreach ($this->createQueryBuilder('t')
            ->select('t.nom')
            ->distinct(true)
            ->orderBy('t.nom', 'ASC')
            ->getQuery()
            ->getResult() as $cols) {
            $nom[] = $cols['nom'];
        }
        return $nom;
    }

    //    public function findOneBySomeField($value): ?TypeCaracteristiques
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
