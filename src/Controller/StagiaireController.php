<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="app_stagiaire")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $stagiaires = $doctrine->getRepository(Stagiaire::class)->findAll();
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

    /**
     * @Route("/stagiaire/add", name="add_stagiaire")
     */
    public function addStagiaire( ManagerRegistry $doctrine, Stagiaire $stagiaire= null, Request $request): Response
    {
        if(!$stagiaire){
            $stagiaire=new Stagiaire();
        }
        $sessions = $doctrine->getRepository(Stagiaire::class)->findAll();
        $entityManager = $doctrine->getManager();// permet dacceder et inserer/modifier en base de donnée
        $form = $this->createForm(StagiaireType::class, $stagiaire);//  quel objet on va creer
        $form->handleRequest($request);//analyser la requete du formulaire
        
        if($form->isSubmitted() && $form->isValid()){// si j'ai soumis et verifie que les champ bien valide que ya pas dinjection mal veillante dans mon formulaire
            
            $stagiaire = $form->getData();//construit la formation avec les données que j'ai spécifier
            $entityManager->persist($stagiaire);//creer l'objet stagiaire
            $entityManager->flush();// insert les element dans  les données dans la base
            return $this->redirectToRoute('app_stagiaire');
        }
        return $this->render('/stagiaire/addedit.html.twig', [
            'formAddStagiaire' => $form->createView(),
            'sessions' => $sessions,
        ]);
    }

    
    /**
     * @Route("/stagiaire/{id}/edit", name="edit_stagiaire")
     */
    public function editStagiaire(ManagerRegistry $doctrine, Stagiaire $stagiaire= null,Request $request): Response{
        if(!$stagiaire){
            $stagiaire=new Stagiaire();
        }
        $entityManager = $doctrine->getManager();// permet dacceder et inserer/modifier en base de donnée
        $form = $this->createForm(StagiaireType::class, $stagiaire);//  quel objet on va creer
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($stagiaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_stagiaire');
        }   
        return $this->render('/stagiaire/addedit.html.twig',[
        'formAddStagiaire'=> $form->createView(),
        'editMode'=> $stagiaire->getId()!==null
        ]);
    }

    /**
     * @Route("/stagiaire/{id}/remove", name="remove_stagiaire")
     */
    public function removeStagiaire(Stagiaire $stagiaire= null, ManagerRegistry $doctrine): Response{

            $manager = $doctrine->getManager();
            $manager->remove($stagiaire);
            $manager->flush();
            return $this->redirectToRoute('app_stagiaire');
    } 

    /**
     * @Route("/stagiaire/{id}", name="show_stagiaire")
     */
    public function show(Stagiaire $stagiaire): Response
    {       
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }
   
}


// /*** @Route("/session/{idSession}/removeStagiaire/{idStagiaire}", name="removeStagiaire_session")*
//  * @ParamConverter("session", options={"mapping": {"idSession": "id"}})
//  * @ParamConverter("stagiaire", options={"mapping": {"idStagiaire": "id"}})*/
// public function removeStagiaire(ManagerRegistry$doctrine, Stagiaire $stagiaire,Session $session){
//     $entityManager = $doctrine->getManager();
//     $session->removeStagiaire($stagiaire);
//     $entityManager->flush();
//     return $this->redirectToRoute('show_session', ['id' => $session->getId(),]);}

