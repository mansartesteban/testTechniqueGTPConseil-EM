<?php
/**
 * Created by PhpStorm.
 * User: Esteban
 * Date: 06/03/2019
 * Time: 09:07
 */

namespace App\Controller;


use App\Entity\Employee;
use App\Entity\Task;
use App\Repository\EmployeeRepository;
use App\Repository\TaskRepository;
use Doctrine\Common\Persistence\ObjectManager;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EmployeeController
 * @package App\Controller
 * @IsGranted("ROLE_EMPLOYEE")
 */
class EmployeeController extends AbstractController
{

    /**
     * @Route("/dashboard", name="employee", methods={"GET"})
     */
    public function dashboard() {
        return $this->render("employee/employee.html.twig", [
            "employeeId" => 93
        ]);
    }


    /**
     * @Route("/employee/done/{id<\d+>}", name="employee_done", methods={"POST"})
     * @param Task $task
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function done(Task $task, ObjectManager $em) {
        try {
            $task->setDone(true);
            $em->persist($task);
            $em->flush();
        } catch (\Exception $ex) {
            return ($this->json(["error" => "Une erreur interne est survenue (1)"]));
        }
        return $this->json([]);
    }

    /**
     * @Route("/employee/{id<\d+>}/tasks", name="employee_list", methods={"GET"})
     * @param Employee $employee
     * @param TaskRepository $taskRepository
     * @param SerializerInterface $jms
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function employeeTasks(Employee $employee, TaskRepository $taskRepository, SerializerInterface $jms) {
        $ret = [];
        if (null === $employee) {
            // Si aucun employé est renseigné, on retourne toutes les tâches !
            // (!) Je sais bien que sur un environnement de production il ne faut pas faire ça,
            // mais la pour la démo, je me permets

            $tasks = $taskRepository->findAll();

        } else { // Récupère les tâches pour un utilisateur précis
            $tasks = $taskRepository->findBy([
                "employee" => $employee
            ]);
        }
        foreach ($tasks as $task) {
            $ret[] = [
                "id" => $task->getId(),
                "title" => $task->getLabel(),
                "start" => $task->getStartAt(),
                "end" => $task->getEndAt(),
                "done" => $task->getDone()
            ];
        }
        return $this->json($ret);
    }
}