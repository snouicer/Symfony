<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\ProgrammeType;
use App\Repository\StagiaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="app_session")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $sessions = $doctrine->getRepository(Session::class)->findAll();
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    /**
     * @Route("/session/add", name="add_session")
     * @Route("/session/{id}/edit", name="edit_session")
     */
    public function addSession(ManagerRegistry $doctrine, Session $session= null, Request $request): Response
    {
        if(!$session){
            $session=new Session();
        }
        $entityManager = $doctrine->getManager();// permet dacceder et inserer/modifier en base de donnée
        $form = $this->createForm(SessionType::class, $session);//  quel objet on va creer
        $form->handleRequest($request);//analyser la requete du formulaire
        
        if($form->isSubmitted() && $form->isValid()){// si j'ai soumis et verifie que les champ bien valide que ya pas dinjection mal veillante dans mon formulaire
            
            $session = $form->getData();//construit la formation avec les données que j'ai spécifier
            $entityManager->persist($session);//creer l'objet formation
            $entityManager->flush();// insert les élements dans les données dans la base

            // $form->get('formation')->getData();
            // $form->get('stagiaires')->getData();
            // // $entityManager->persist($form);//creer l'objet formation
            // $entityManager->flush();

            
            return $this->redirectToRoute('app_session');
        }
        return $this->render('/session/addedit.html.twig', [
            'formAddSession' => $form->createView(),
            'editMode'=> $session->getId()!==null
        ]);
    }
    

    /**
     * @Route("/session/{id}/remove", name="remove_session")
     */
    public function removeSession(Session $session= null, ManagerRegistry $doctrine): Response{
        
            $manager = $doctrine->getManager();
            $manager->remove($session);
            $manager->flush();
            return $this->redirectToRoute('app_session');
    } 

    /**
     * @Route("/session/add_programme", name="add_Programme")
     * @Route("/session/{id}/edit_programme", name="edit_Programme")
     */
    public function addeditProgramme(ManagerRegistry $doctrine, Programme $programme= null, Request $request): Response
    {
        if(!$programme){
            $programme=new Programme();
        }
        $entityManager = $doctrine->getManager();// permet dacceder et inserer/modifier en base de donnée
        $form = $this->createForm(ProgrammeType::class, $programme);//  quel objet on va creer
        $form->handleRequest($request);//analyser la requete du formulaire
        
        if($form->isSubmitted() && $form->isValid()){// si j'ai soumis et verifie que les champ bien valide que ya pas dinjection mal veillante dans mon formulaire
            
            $programme = $form->getData();//construit la formation avec les données que j'ai spécifier
            $entityManager->persist($session);//creer l'objet formation
            $entityManager->flush();// insert les element dans  les données dans la base
            $form->get('programmes')->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }
        return $this->render('/session/addeditProgramme.html.twig', [
            'formAddProgramme' => $form->createView(),
            'editMode'=> $programme->getId()!==null
        ]);
    }

   /**
     * @Route("/session/{id}", name="show_session")
     */
    public function show(Session $session, StagiaireRepository $stgrNnInsc, ManagerRegistry $doctrine): Response
    {
        $stagiaires = $doctrine->getRepository(Session::class)->findAll();
        $stagiaireNonInscrits = $stgrNnInsc->getStagiaireNonInscrits($session->getId());
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires,
            'stagiaireNonInscrits' => $stagiaireNonInscrits,
        ]);
    }


}
