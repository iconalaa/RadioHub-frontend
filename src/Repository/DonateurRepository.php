<?php

namespace App\Repository;

use App\Entity\Donateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;


/**
 * @extends ServiceEntityRepository<Donateur>
 *
 * @method Donateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Donateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Donateur[]    findAll()
 * @method Donateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Donateur::class);
    }

    public function sms(String $num) : void
    {
        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'ACe1c72f1ecdb0a7c816847cf580845632';
        $auth_token = '9f87bfd3732f652d16896de2930d7d16';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+15089284103";

        $client = new Client($sid, $auth_token);
        $client->messages->create(
            // the number you'd like to send the message to
            $num,
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => $twilio_number,
                // the body of the text message you'd like to send
                'body' => 'Your have been registered as a donor at RadioHub. If you have no recollection of this please email us at contact@radiohub.com'
            ]
        );
    }
    

//    /**
//     * @return Donateur[] Returns an array of Donateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Donateur
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
