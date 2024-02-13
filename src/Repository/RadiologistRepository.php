<?php

namespace App\Repository;

use App\Entity\Radiologist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Radiologist>
 *
 * @method Radiologist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Radiologist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Radiologist[]    findAll()
 * @method Radiologist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RadiologistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Radiologist::class);
    }

//    /**
//     * @return Radiologist[] Returns an array of Radiologist objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Radiologist
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
