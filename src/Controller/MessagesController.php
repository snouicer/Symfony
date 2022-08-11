<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    /**
     * @Route("/messages", name="app_messages")
     */
    public function index(): Response
    {
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    /**
     * @Route("/send", name="app_send")
     */
    public function send(Request $request, ManagerRegistry $doctrine): Response
    {
        $message= new Messages;
        $formSend= $this->createForm(MessagesType::class, $message);
        //traitement des données
        $formSend->handleRequest($request);//analyser la requete du formulaire
        
        if($formSend->isSubmitted() && $formSend->isValid()){// si j'ai soumis et verifie que les champ bien valide que ya pas dinjection mal veillante dans mon formulaire
            $message->setSender($this->getUser());
            // $entityManager=$this->getDoctrine()->getManager(); //deprecied
            $entityManager = $doctrine->getManager();
            $entityManager->persist($message);//creer l'objet message
            $entityManager->flush();// insert les elements (les données) dans la base de donnée
            $this->addFlash("message", "Votre message a été envoyé avec succes");
            
            return $this->redirectToRoute('app_messages');
        }

        return $this->render('messages/send.html.twig', [
            'formSend' => $formSend->createView()
        ]);

    }

    /**
     * @Route("/received", name="app_received")
     */
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }

    /**
     * @Route("/sent", name="app_sent")
     */
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }

    /**
     * @Route("/read/{id}", name="app_read")
     */
    public function read(Messages $message, ManagerRegistry $doctrine): Response
    {   $message->setIsRead(true); // je le lis je l'ouvre
        $entityManager = $doctrine->getManager();
        $entityManager->persist($message);//creer l'objet message
        $entityManager->flush();// insert les elements (les données) dans la base de donnée
       

        return $this->render('messages/read.html.twig', compact("message"));
    }

    /**
     * @Route("/removeMessage/{id}", name="app_remove_message")
     */
    public function removeMessage(Messages $message, ManagerRegistry $doctrine): Response
    {   
        $entityManager = $doctrine->getManager();
        $entityManager->remove($message);
        $entityManager->flush();// insert les elements (les données) dans la base de donnée
       

        return $this->redirectToRoute("app_received");
    }


}
