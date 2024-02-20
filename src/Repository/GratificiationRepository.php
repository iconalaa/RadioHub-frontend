<?php

namespace App\Repository;

use App\Entity\Gratificiation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gratificiation>
 *
 * @method Gratificiation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gratificiation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gratificiation[]    findAll()
 * @method Gratificiation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GratificiationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gratificiation::class);
    }

//    /**
//     * @return Gratificiation[] Returns an array of Gratificiation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Gratificiation
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
