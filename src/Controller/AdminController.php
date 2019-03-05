<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\MyVendor\MyDateTime;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function adminCreate(Request $request) {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $tmpTask = $request->get("task");
        $tmpTask["start_at"] = new MyDateTime($request->get("start_at"));
        $tmpTask["end_at"] = new MyDateTime($request->get("end_at"));
        $request->request->set("task", $tmpTask);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //TODO: Gérer le temps maximum alloué à un employé

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute("admin_task", ["id" => $task->getId()]);
        }

//        $form->setData(["start_at" => $form->getViewData()->getStartAt()->format("Y-m-d H:i:s")]);
//        $form->setData(["end_at" => $form->getViewData()->getEndAt()->format("Y-m-d H:i:s")]);
//        dump($form);
        return ($this->render("admin/newTask.html.twig", [
            "task" => $task,
            "form" => $form->createView(),
            "error" => $form->getErrors()
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function adminEdit(Task $task, Request $request) {
        $form = $this->createForm(TaskType::class, $task);

        // TODO : Gérer si le format de date est correct
        $tmpTask = $request->request->get("task");
        $tmpTask["start_at"] = new MyDateTime($request->get("start_at"));
        $tmpTask["end_at"] = new MyDateTime($request->get("end_at"));
        $request->request->set("task", $tmpTask);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('admin_task', ["id" => $task->getId()]);
        }

        return ($this->render("admin/edit.html.twig", [
            "form" => $form->createView(),
            "task" => $task
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
