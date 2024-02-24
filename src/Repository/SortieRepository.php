<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findFilteredSorties(array $criteria) {
        // TODO toutes les filtrage et queries, etc

        $qb = $this->createQueryBuilder('s');

        if (!empty($criteria['nom'])) {
            $qb->andWhere('s.nom LIKE :sortieNom')
                ->setParameter('sortieNom', '%' . $criteria['nom'] . '%');
        }

        if (!empty($criteria['dateDebut'])) {
            $qb->andWhere('s.dateDebut >= :date')
                ->setParameter('date', $criteria['dateDebut']);
        }

        if (!empty($criteria['endDate'])) {
            $qb->andWhere('s.endDate <= :endDate')
                ->setParameter('endDate', $criteria['endDate']);
        }

        if (!empty($criteria['site'])) {
            $qb->andWhere('s.site = :id')
                ->setParameter('id', $criteria['site']);
        }

        return $qb->getQuery()->getResult();

    }

}
