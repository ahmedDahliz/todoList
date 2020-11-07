<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Todo;
use App\Repository\TodoRepository;

class TodoController extends AbstractController
{
    /**
     * @Route("/", name="todoList")
     */
    public function index(TodoRepository $todoRepository): Response
    {
        $todos = $todoRepository->findAll();

        return $this->render('todo/index.html.twig', [
            'todos' => $todos,
        ]);
    }

    /**
     * @Route("/addTask", name="addTask")
     */
    public function add(Request $request): JsonResponse
    {
        try {

            $em = $this->getDoctrine()->getManager();
            $todo = new Todo();
            $todo->setTask($request->request->get('task'));
            $todo->setStatus('New');
            
            $em->persist($todo);
            $em->flush();
            
            return new JsonResponse(['message'=>'Add with success', 'task'=> $todo->getTask(), 'id'=> $todo->getId()], 200);
        }catch(Exception $e) {
            return new JsonResponse('Somting went wrong', 500);
        }
    }

    /**
     * @Route("/editTaskStatus", name="editTaskStatus")
     */
    public function editStatus(Request $request, TodoRepository $todoRepository): JsonResponse
    {
        try {

            $em = $this->getDoctrine()->getManager();
            $todo = $todoRepository->find($request->request->get('id'));;
            $todo->setStatus($request->request->get('status'));
            
            $em->flush();

            return new JsonResponse(['message'=>'Satus has been edited successfully', 'status'=> $todo->getStatus(), 'id'=> $todo->getId()], 200);
        }catch(Exception $e) {
            return new JsonResponse('Somting went wrong', 500);
        }
    }
}
