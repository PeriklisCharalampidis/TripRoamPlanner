<?php

namespace App\Repository;

use App\Entity\PakingList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PakingList>
 *
 * @method PakingList|null find($id, $lockMode = null, $lockVersion = null)
 * @method PakingList|null findOneBy(array $criteria, array $orderBy = null)
 * @method PakingList[]    findAll()
 * @method PakingList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PakingListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PakingList::class);
    }

//    /**
//     * @return PakingList[] Returns an array of PakingList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PakingList
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
