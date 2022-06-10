<?php

namespace App\Repository;

use App\Entity\TypeCaracteristiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

//    /**
//     * @return TypeCaracteristiques[] Returns an array of TypeCaracteristiques objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

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
