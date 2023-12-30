<?php

namespace App\Repository;

use App\Entity\Tricks;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Tricks>
 *
 * @method Tricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tricks[]    findAll()
 * @method Tricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 15;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tricks::class);
    }

    public function getTricksPaginator(int $page): Paginator
    {  
        $offset = self::PAGINATOR_PER_PAGE * ($page - 1);
       $query = $this->createQueryBuilder('t')
           ->orderBy('t.id', 'DESC')
           ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
           ->getQuery();

        return new Paginator($query);
    }
}
