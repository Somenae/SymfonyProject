<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function remove(Users $users): Users
    {
        $this->getEntityManager()->remove($users);
        $this->getEntityManager()->flush();
        return $users;
    }

    public function save(Users $users): Users
    {
        $this->getEntityManager()->persist($users);
        $this->getEntityManager()->flush();
        return $users;
    }


    public function search($request)
    {
        return $this->createQueryBuilder('u')
            ->where('u.firstname LIKE :query')
            ->orWhere('u.lastname LIKE :query')
            ->orWhere('u.email LIKE :query')
            ->setParameter('query', '%' . $request . '%')
            ->getQuery()
            ->getResult();
    }

    public function countSearchResults($request)
{
    $queryBuilder = $this->createQueryBuilder('u');
    $queryBuilder->select('count(u.id)')
        ->where('u.firstname LIKE :query')
        ->orWhere('u.lastname LIKE :query')
        ->orWhere('u.email LIKE :query')
        ->setParameter('query', '%'.$request.'%');

    $query = $queryBuilder->getQuery();

    return $query->getSingleScalarResult();
}

public function countUsers()
{
    $queryBuilder = $this->createQueryBuilder('u');
    $queryBuilder->select('count(u.id)');

    $query = $queryBuilder->getQuery();

    return $query->getSingleScalarResult();
}

//    /**
//     * @return Users[] Returns an array of Users objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Users
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
