<?php

namespace App\Repository;

use App\Entity\TricksGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TricksGroup>
 *
 * @method TricksGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method TricksGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method TricksGroup[]    findAll()
 * @method TricksGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TricksGroup::class);
    }
}
