<?php

namespace App\Repository;

use App\Entity\TricksImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TricksImage>
 *
 * @method TricksImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TricksImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TricksImage[]    findAll()
 * @method TricksImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TricksImage::class);
    }

//    /**
//     * @return TricksImage[] Returns an array of TricksImage objects
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

//    public function findOneBySomeField($value): ?TricksImage
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
