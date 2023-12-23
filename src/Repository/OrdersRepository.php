<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 *
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }
      /**
     * @return Orders[] Returns an array of Orders objects
     */
    public function findByAttributeOffset($val, $field, $offset=0): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.'.$field.' like :val')
            ->setParameter('val', '%'.$val.'%')
            ->orderBy('o.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByAttributeCount($val, $field)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.'.$field.' like :val')
            ->setParameter('val', '%'.$val.'%')
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Orders[] Returns an array of Orders objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Orders
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
