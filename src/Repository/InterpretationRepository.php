<?php

namespace App\Repository;

use App\Entity\Interpretation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Interpretation>
 *
 * @method Interpretation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interpretation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interpretation[]    findAll()
 * @method Interpretation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterpretationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interpretation::class);
    }

//    /**
//     * @return Interpretation[] Returns an array of Interpretation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Interpretation
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
