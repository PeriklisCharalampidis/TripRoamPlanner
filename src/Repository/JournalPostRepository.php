<?php

namespace App\Repository;

use App\Entity\JournalPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JournalPost>
 *
 * @method JournalPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalPost[]    findAll()
 * @method JournalPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JournalPost::class);
    }

//    /**
//     * @return JournalPost[] Returns an array of JournalPost objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?JournalPost
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
