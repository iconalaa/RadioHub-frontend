<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Report>
 *
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    public function findByField(string $query): array
    {
        // Trim leading and trailing spaces from the query
        $trimmedQuery = trim($query);

        return $this->createQueryBuilder('cr')
            ->leftJoin('cr.id_image', 'i')
            ->leftJoin('i.patient', 'p')
            ->leftJoin('p.user', 'pu')  // Join with the User entity associated with the Patient
            ->leftJoin('i.radiologist', 'r')
            ->leftJoin('r.user', 'ru')  // Join with the User entity associated with the Radiologist
            ->where('cr.interpretationMed LIKE :query')
            ->orWhere('cr.interpretation_rad LIKE :query')
            ->orWhere('pu.name LIKE :query')  // Search in the name of the patient's user
            ->orWhere('ru.name LIKE :query')  // Search in the name of the radiologist's user
            ->orWhere('cr.date LIKE :dateQuery') // Search in the date field
            ->setParameter('query', '%' . $trimmedQuery . '%') // Use the trimmed query
            ->setParameter('dateQuery', '%' . $trimmedQuery . '%')
            ->getQuery()
            ->getResult();
    }

    public function countReportsByStatus(bool $isEdited): int
    {
        return $this->createQueryBuilder('cr')
            ->select('COUNT(cr.id)')
            ->where('cr.isEdited = :isEdited')
            ->setParameter('isEdited', $isEdited)
            ->getQuery()
            ->getSingleScalarResult();
    }


    //    /**
    //     * @return Report[] Returns an array of Report objects
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

    //    public function findOneBySomeField($value): ?Report
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
