<?php

namespace App\Repository;

use App\Entity\SaleItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SaleItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaleItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaleItem[]    findAll()
 * @method SaleItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleItem::class);
    }

    // /**
    //  * @return SaleItem[] Returns an array of SaleItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SaleItem
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
