<?php

namespace App\Repository;

use App\Entity\TipoGrafico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoGrafico|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoGrafico|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoGrafico[]    findAll()
 * @method TipoGrafico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoGraficoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoGrafico::class);
    }

    // /**
    //  * @return TipoGrafico[] Returns an array of TipoGrafico objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoGrafico
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
