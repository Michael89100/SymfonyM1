<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workshop>
 *
 * @method Workshop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workshop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workshop[]    findAll()
 * @method Workshop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Workshop[]    findByUserAndYear(User $user, int $year)
 * @method Workshop[]    findBySpeaker(User $user)
 * @author Olivier Perdrix
 */
class WorkshopRepository extends ServiceEntityRepository
{
    /**
     * WorkshopRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workshop::class);
    }

    /**
     * Cette méthode permet de récupérer les ateliers d'une édition
     * 
     * @param int $editionYear L'année de l'édition dont on veut récupérer les ateliers
     * @return Workshop[] La liste des ateliers de l'édition
     */
    public function findWorkshopsByYear(int $editionYear): array
    {
        return $this->createQueryBuilder('w')
            ->join('w.edition', 'e')
            ->where('e.year = :editionYear')
            ->setParameter('editionYear', $editionYear)
            ->getQuery()
            ->getResult();
    }

    /**
     * Cette méthode permet de récupérer les ateliers d'un utilisateur en tant qu'étudiant pour une édition donnée
     * 
     * @param User $user L'utilisateur dont on veut récupérer les ateliers
     * @return Workshop[] La liste des ateliers de l'utilisateur
     */
    public function findByUserAndYear(User $user, int $year): array
    {
        return $this->createQueryBuilder('w')
            ->join('w.edition', 'e')
            ->join('w.students', 's')
            ->where('e.year = :year')
            ->andWhere('s.user = :user')
            ->setParameter('year', $year)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Cette méthode permet de récupérer les ateliers d'un utilisateur en tant qu'intervenant
     * 
     * @param User $user L'utilisateur dont on veut récupérer les ateliers
     * @return Workshop[] La liste des ateliers de l'utilisateur
     */
    public function findBySpeaker(User $user): array
    {
        return $this->createQueryBuilder('w')
            ->join('w.speakers', 's')
            ->join('w.edition', 'e')
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->orderBy('e.year', 'DESC')
            ->addOrderBy('w.startAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
