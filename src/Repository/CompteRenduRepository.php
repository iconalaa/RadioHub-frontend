<?php

namespace App\Repository;

use App\Entity\CompteRendu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompteRendu>
 *
 * @method CompteRendu|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompteRendu|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompteRendu[]    findAll()
 * @method CompteRendu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteRenduRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompteRendu::class);
    }

//    /**
//     * @return CompteRendu[] Returns an array of CompteRendu objects
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

//    public function findOneBySomeField($value): ?CompteRendu
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
