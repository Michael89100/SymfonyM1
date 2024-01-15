<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    /**
     * Get all questions of a quiz.
     *
     * @param int $quizId The ID of the quiz.
     * @return array The array of questions.
     */
    public function getAllQuestions($quizId): array
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.quizId = :quizId')
            ->setParameter('quizId', $quizId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Remove a question from a quiz.
     *
     * @param int $quizId The ID of the quiz.
     * @param int $questionId The ID of the question to remove.
     * @return void
     */
    public function removeQuestion($quizId, $questionId): void
    {
        $this->createQueryBuilder('q')
            ->delete()
            ->andWhere('q.quizId = :quizId')
            ->andWhere('q.id = :questionId')
            ->setParameter('quizId', $quizId)
            ->setParameter('questionId', $questionId)
            ->getQuery()
            ->execute();
    }

//    /**
//     * @return Quiz[] Returns an array of Quiz objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Quiz
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
