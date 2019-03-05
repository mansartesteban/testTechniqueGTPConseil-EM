<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function overlapTask(Task $newTask) {
        $em = $this->getEntityManager();
        $ret = $em->createQuery("SELECT t 
            FROM App\Entity\Task t
            WHERE t.employee = :employee
            AND (t.start_at <= :start
            AND t.end_at >= :end
            OR t.start_at >= :start
            AND t.end_at <= :end
            OR t.start_at <= :end
            AND t.end_at >= :end
            OR t.start_at <= :start
            AND t.end_at >= :start)")
            ->setParameters([
                "start" => $newTask->getStartAt(),
                "end" => $newTask->getEndAt(),
                "employee" => $newTask->getEmployee()->getId()
            ])
            ->execute()
        ;
        return $ret;
    }

    public function howManyHoursThisDay($newTask) {
        $em = $this->getEntityManager();
        $ret = $em->createQuery("SELECT t 
            FROM App\Entity\Task t
            WHERE t.employee = :employee
            AND (t.start_at BETWEEN :start AND :end)")
            ->setParameters([
                "start" => new \DateTime($newTask->getStartAt()->format("Y-m-d 00:00:00")),
                "end" => new \DateTime($newTask->getEndAt()->format("Y-m-d 23:59:59")),
                "employee" => $newTask->getEmployee()
            ])
            ->execute()
        ;
        $tot = 0;
        foreach ($ret as $res) {
            $tot += $res->getStartAt()->diff($res->getEndAt())->h;
        }
        return $tot;
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
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
    public function findOneBySomeField($value): ?Task
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
