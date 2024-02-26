<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\User\UserInterface;
use function PHPUnit\Framework\isFalse;

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

    public function findFilteredSorties( UserInterface $user, array $criteria) {
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

        if (!empty($criteria['dateFin'])) {
            $qb->andWhere('s.dateDebut <= :dateFin')
                ->setParameter('dateFin', $criteria['dateFin']);
        }

        if (!empty($criteria['site'])) {
            $qb->andWhere('s.site = :id')
                ->setParameter('id', $criteria['site']);
        }

        if (!empty($criteria['checkOrganisateur'])) {
            $qb->andWhere('s.organisateur = :user')
                ->setParameter('user', $user);
        }

        if (!empty($criteria['checkParticipant'])) {
            $qb->andWhere(':user MEMBER OF s.participants')
                ->setParameter('user', $user);
        }

        if (!empty($criteria['checkNonParticipant'])) {
            $qb->andWhere(':user NOT MEMBER OF s.participants')
                ->setParameter('user', $user);

        }

        if (!empty($criteria['datePasse'])) {
            $dateNow = new \DateTime();
            $qb->andWhere('s.dateDebut < :dateNow')
            ->setParameter('dateNow', $dateNow);
        }
        return $qb->getQuery()->getResult();

    }
}
