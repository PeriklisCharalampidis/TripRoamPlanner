<?php

namespace App\Repository;

use App\Entity\TripPackingListItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TripPackingListItem>
 *
 * @method TripPackingListItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TripPackingListItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TripPackingListItem[]    findAll()
 * @method TripPackingListItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripPackingListItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TripPackingListItem::class);
    }

//    /**
//     * @return TripPackingListItem[] Returns an array of TripPackingListItem objects
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

//    public function findOneBySomeField($value): ?TripPackingListItem
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
