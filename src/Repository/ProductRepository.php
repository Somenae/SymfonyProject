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

    public function searchByName(string $name): ?array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name like :val')
            ->setParameter('val', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }
    public function searchByIDSale(): ?array
    {
        return $this->createQueryBuilder('p')
            ->where('p.ProductSales >= 0')
            ->getQuery()
            ->getResult();
    }

    public function getRandomProductPromo()
{
    $sql = "SELECT * FROM `product` WHERE `product_sales_id` IS NOT NULL ORDER BY RAND() LIMIT 6";
    $query = $this->getEntityManager()->getConnection()
        ->executeQuery($sql);
    $result = $query->fetchAllAssociative();

    $productsPromo = [];
    foreach ($result as $preproductPromo) {
        $productPromo = $this->find($preproductPromo['id']);
        $productsPromo[] = $productPromo;
    }
    return $productsPromo;
}

    public function getRandomProduct()
    {
        $sql = "SELECT * FROM `product` ORDER BY RAND() LIMIT 6";
        $query = $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
        $result = $query->fetchAllAssociative();

        $products = [];
        foreach ($result as $preproduct) {
            $product = $this->find($preproduct['id']);
            $products[] = $product;
        }
        // var_dump($products);
        return $products;
    }

    public function getRandomProductNoPromo()
    {
        $sql = "SELECT * FROM `product` WHERE `product_sales_id` IS NULL ORDER BY RAND() LIMIT 6";
        $query = $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
        $result = $query->fetchAllAssociative();

        $productsNoPromo = [];
        foreach ($result as $preproduct) {
            $productNoPromo = $this->find($preproduct['id']);
            $productsNoPromo[] = $productNoPromo;
        }
        // var_dump($products);
        return $productsNoPromo;
    }

    public function countProducts()
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->select('count(u.id)');

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }

    public function search($request)
    {
        return $this->createQueryBuilder('u')
            ->where('u.name LIKE :query')
            ->orWhere('u.price LIKE :query')
            ->orWhere('u.description LIKE :query')
            ->orWhere('u.image LIKE :query')
            ->setParameter('query', '%' . $request . '%')
            ->getQuery()
            ->getResult();
    }

    public function countSearchResults($request)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->select('count(u.id)')
            ->where('u.name LIKE :query')
            ->orWhere('u.price LIKE :query')
            ->orWhere('u.description LIKE :query')
            ->orWhere('u.image LIKE :query')
            ->setParameter('query', '%' . $request . '%');

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
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
