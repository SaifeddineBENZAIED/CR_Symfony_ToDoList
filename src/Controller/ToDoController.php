<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    #[Route('/todolist', name: 'app_list_to_do')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (!($session->has('todos'))) {
            $todos = [
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            ];
            $session->set('todos', $todos);
        }
        return $this->render('to_do/index.html.twig');
    }
    #[Route('/todolist/add/{nom}/{contenu}', name: 'addtodo')]
    public function addToDo(Request $request, $nom, $contenu):Response{
        $session = $request->getSession();
        if($session->has('todos')){
            $t=$session->get('todos');
            if(isset($t[$nom])){
                $this->addFlash('error',"C'est ToDo est déja existant");
            }else {
                $t[$nom] = $contenu;
                $session->set('todos',$t);
                $this->addFlash('success',"ToDo ajouté avec succès");
            }
        }
        else{
            $this->addFlash('error',"Tu dois initialiser votre ToDoList en premier lieu");
        }
        return $this->redirectToRoute('app_list_to_do');
    }
    #[Route('/todolist/delete/{nom}', name: 'deletetodo')]
    public function deleteToDo(Request $request, $nom):Response{
        $session = $request->getSession();
        if($session->has('todos')){
            $t=$session->get('todos');
            if(isset($t[$nom])){
                unset($t[$nom]);
                $session->set('todos',$t);
                $this->addFlash('success',"ToDo supprimé avec succès");
            }else {
                $this->addFlash('error',"C'est ToDo n'existe pas");
                $session->set('todos',$t);
            }
        }
        else{
            $this->addFlash('error',"Tu dois initialiser votre ToDoList en premier lieu");
        }
        return $this->redirectToRoute('app_list_to_do');
    }
    #[Route('/todolist/reset', name: 'resettodo')]
    public function resetToDo(Request $request):Response{
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = [
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            ];
            $session->set('todos', $todos);
        }
        else{
            $this->addFlash('error',"Tu dois initialiser votre ToDoList en premier lieu");
        }
        return $this->redirectToRoute('app_list_to_do');
    }
}
