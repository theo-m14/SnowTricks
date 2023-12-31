<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Tricks;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{   
    public const PAGINATOR_PER_PAGE = 4;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getCommentPaginator(Tricks $trick, int $page): Paginator
    {  
        $offset = self::PAGINATOR_PER_PAGE * ($page - 1);
       $query = $this->createQueryBuilder('c')
           ->andWhere('c.trick = :trick')
           ->setParameter('trick', $trick)
           ->orderBy('c.id', 'DESC')
           ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
           ->getQuery();

        return new Paginator($query);
    }
}
