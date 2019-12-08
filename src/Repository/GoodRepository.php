<?php

namespace App\Repository;

use App\Entity\Good;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Good|null find($id, $lockMode = null, $lockVersion = null)
 * @method Good|null findOneBy(array $criteria, array $orderBy = null)
 * @method Good[]    findAll()
 * @method Good[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Good::class);
    }

    public function findAllDescSentAt()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.createdAt', 'DESC')
            ->setMaxResults(16)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOneById($id)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function findAllByUserId($id)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.seller = :id')
            ->setParameter('id', $id)
            ->orderBy('g.createdAt','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function search($content, $min_price, $max_price, $subcategory_id)
    {
        return $this->createQueryBuilder('g')
            ->where('g.title LIKE :content')->setParameter('content', '%'.$content.'%')
            ->andWhere('g.price >= :min_price')->setParameter('min_price', $min_price)
            ->andWhere('g.price <= :max_price')->setParameter('max_price', $max_price)
            ->andWhere('g.subcategory = :subcategory')->setParameter('subcategory', $subcategory_id)
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Good[] Returns an array of Good objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Good
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
