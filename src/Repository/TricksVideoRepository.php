<?php

namespace App\Repository;

use App\Entity\TricksVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TricksVideo>
 *
 * @method TricksVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TricksVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TricksVideo[]    findAll()
 * @method TricksVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TricksVideo::class);
    }

//    /**
//     * @return TricksVideo[] Returns an array of TricksVideo objects
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

//    public function findOneBySomeField($value): ?TricksVideo
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
