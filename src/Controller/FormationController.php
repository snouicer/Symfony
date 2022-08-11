<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="app_formation")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        //creer une variable qui permet de recuperer toute les formations
        $formations = $doctrine->getRepository(Formation::class)->findAll();// recupere un tableau d'objet formation equivaut a Select* From sur SQL
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    /**
     * @Route("/formation/add", name="add_formation")
     * @Route("/formation/{id}/edit", name="edit_formation")
     */
    public function addFormation(ManagerRegistry $doctrine, Formation $formation= null, Request $request): Response
    {
        if(!$formation){
            $formation=new Formation();
        }
        $entityManager = $doctrine->getManager();// permet dacceder et inserer/modifier en base de donnée
        $form = $this->createForm(FormationType::class, $formation);//  quel objet on va creer
        $form->handleRequest($request);//analyser la requete du formulaire
        
        if($form->isSubmitted() && $form->isValid()){// si j'ai soumis et verifie que les champ bien valide que ya pas dinjection mal veillante dans mon formulaire
            
            $formation = $form->getData();//construit la formation avec les données que j'ai spécifier
            $entityManager->persist($formation);//creer l'objet formation
            $entityManager->flush();// insert les element dans  les données dans la base
            
            return $this->redirectToRoute('app_formation');
        }
        return $this->render('/formation/addedit.html.twig', [
            'formAddFormation' => $form->createView(),
            'editMode'=> $formation->getId()!==null
        ]);
    }


     /**
      * @Route("/{id}/remove", name="remove_formation")
      */
    public function removeFormation(Formation $formation= null, ManagerRegistry $doctrine): Response{   
            $entityManager = $doctrine->getManager();
            $entityManager->remove($formation);
            $entityManager->flush();
            return $this->redirectToRoute('app_formation');
    } 

    /**
      * @Route("/formation/{id}", name="show_formation")
      */
     public function show(Formation $formation): Response
     {
         return $this->render('formation/show.html.twig', [
             'formation' => $formation,
         ]);
     }
}
