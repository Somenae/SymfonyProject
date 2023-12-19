<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //  public function findAllGreaterThanPrice(): array
    //  {
    //      $entityManager = $this->getEntityManager();

    //      $query = $entityManager->createQuery(
    //          'SELECT p
    //          FROM App\Entity\Product p
    //          ORDER BY "RAND()"
    //          LIMIT 4'
    //      );

         // returns an array of Product objects
    //     return $query->getResult();
    // }

    public function getRandomProduct()
    {
        $sql = "SELECT * FROM `product` ORDER BY RAND() LIMIT 6";
        $query = $this->getEntityManager()->getConnection()
                ->executeQuery($sql);
        $result = $query->fetchAllAssociative();
       
        $products = [];
        foreach($result as $preproduct) {
            $product = $this->find($preproduct['id']);
            $products[] = $product;
        }
        // var_dump($products);
        return $products;
    }
    



//    /**
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
