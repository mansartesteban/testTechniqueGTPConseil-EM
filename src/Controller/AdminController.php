<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\MyVendor\MyDateTime;
use App\Repository\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     * @param TaskRepository $taskRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function admin(TaskRepository $taskRepository)
    {
        return $this->render('admin/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    /**
     * @Route("/create", name="admin_create")
     * @param Request $request
     * @param TaskRepository $taskRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function adminCreate(Request $request, TaskRepository $taskRepository) {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        if ($tmpTask = $request->request->get("task")) { // Si une tâche est postée
            $tmpTask["start_at"] = new MyDateTime($tmpTask["start_at"]);
            $tmpTask["end_at"] = new MyDateTime($tmpTask["end_at"]);
            $request->request->set("task", $tmpTask);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if (!($overlap = $taskRepository->overlapTask($task))) { // Si un chevauchement d'horaire est détecté
                    if (($time = $taskRepository->howManyHoursThisDay($task)) && $time > 0) { // Compte le nombre d'heure travaillées dans la journée
                        if ($time >= 8) { // Si plus de 8h
                            $moreErrors = "L'employé a déjà atteint ses 8h de travail pour ajourd'hui";
                        } else if ($time + $task->getStartAt()->diff($task->getEndAt())->h > 8) { // Si la tâche qu'on lui ajoute fait dépasser la limite des 8h
                            $moreErrors = "La tâche que vous êtes sur le point d'ajouter va dépasser la limite de temps de travail pour cet employé";
                        } else {
                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($task);
                            $entityManager->flush();
                            return $this->redirectToRoute("admin_task", ["id" => $task->getId()]);
                        }
                    } else {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($task);
                        $entityManager->flush();
                        return $this->redirectToRoute("admin_task", ["id" => $task->getId()]);
                    }
                } else {
                    $overlap = $overlap[0];
                    $moreErrors = "Chevauchement avec tâche "
                        . $overlap->getId() . " (" . $overlap->getStartAt()->format("d/m/Y H:i")
                        . " - " . $overlap->getEndAt()->format("d/m/Y H:i") . ")";

                }
            }
        }
//        $form->setData(["start_at" => $form->getViewData()->getStartAt()->format("Y-m-d H:i:s")]);
//        $form->setData(["end_at" => $form->getViewData()->getEndAt()->format("Y-m-d H:i:s")]);
//        dump($form);
        return ($this->render("admin/newTask.html.twig", [
            "task" => $task,
            "form" => $form->createView(),
            "error" => $form->getErrors(),
            "moreError" => $moreErrors ?? ""
        ]));
    }

    /**
     * @Route("/list", name="admin_list", methods={"GET"})
     * @param TaskRepository $taskRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminList(TaskRepository $taskRepository) {
        return $this->render('admin/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    /**
     * @Route("/task/{id<\d+>}", name="admin_task", methods={"GET"})
     * @param Task $task
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminTask(Task $task) {
        return $this->render('admin/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/edit/{id<\d+>}", name="admin_edit", methods={"GET", "POST"})
     * @param Task $task
     * @param Request $request
     * @param TaskRepository $taskRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function adminEdit(Task $task, Request $request, TaskRepository $taskRepository) {
        $form = $this->createForm(TaskType::class, $task);

        if ($tmpTask = $request->request->get("task")) {
            $tmpTask["start_at"] = new MyDateTime($tmpTask["start_at"]);
            $tmpTask["end_at"] = new MyDateTime($tmpTask["end_at"]);
            $request->request->set("task", $tmpTask);
            $form->handleRequest($request);

            if (!($overlap = $taskRepository->overlapTask($task))) { // Si un chevauchement d'horaire est détecté
                if (($time = $taskRepository->howManyHoursThisDay($task)) && $time > 0) { // Compte le nombre d'heure travaillées dans la journée
                    if ($time >= 8) { // Si plus de 8h
                        $moreErrors = "L'employé a déjà atteint ses 8h de travail pour ajourd'hui";
                    } else if ($time + $task->getStartAt()->diff($task->getEndAt())->h > 8) { // Si la tâche qu'on lui ajoute fait dépasser la limite des 8h
                        $moreErrors = "La tâche que vous êtes sur le point d'ajouter va dépasser la limite de temps de travail pour cet employé";
                    } else {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($task);
                        $entityManager->flush();
                        return $this->redirectToRoute("admin_task", ["id" => $task->getId()]);
                    }
                } else {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($task);
                    $entityManager->flush();
                    return $this->redirectToRoute("admin_task", ["id" => $task->getId()]);
                }
            } else {
                $overlap = $overlap[0];
                $moreErrors = "Chevauchement avec tâche "
                    . $overlap->getId() . " (" . $overlap->getStartAt()->format("d/m/Y H:i")
                    . " - " . $overlap->getEndAt()->format("d/m/Y H:i") . ")";

            }        }

        return ($this->render("admin/edit.html.twig", [
            "form" => $form->createView(),
            "task" => $task,
            "moreError" => $moreErrors ?? ""
        ]));
    }

    /**
     * @Route("/{id}", name="admin_delete", methods={"DELETE"})
     * @param Request $request
     * @param Task $task
     * @return Response
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_list');
    }
}
