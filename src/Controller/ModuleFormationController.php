<?php

namespace App\Controller;

use App\Entity\ModuleFormation;
use App\Form\ModuleFormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleFormationController extends AbstractController
{
    /**
     * @Route("/moduleformation", name="app_module_formation")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $moduleFormations = $doctrine->getRepository(ModuleFormation::class)->findAll();
        return $this->render('module_formation/index.html.twig', [
            'moduleformations' => $moduleFormations,
        ]);
    }

    /**
     * @Route("/moduleformation/add", name="add_module_formation")
     * @Route("/moduleformation/{id}/edit", name="edit_module_formation")
     */
    public function addModuleFormation( ManagerRegistry $doctrine, ModuleFormation $moduleFormation= null, Request $request): Response
    {
        if(!$moduleFormation){
            $moduleFormation=new ModuleFormation();
        }
        $entityManager = $doctrine->getManager();// permet dacceder et inserer/modifier en base de donnée
        $form = $this->createForm(ModuleFormationType::class, $moduleFormation);//  quel objet on va creer
        $form->handleRequest($request);//analyser la requete du formulaire
        
        if($form->isSubmitted() && $form->isValid()){// si j'ai soumis et verifie que les champ bien valide que ya pas dinjection mal veillante dans mon formulaire
            
            $moduleformation = $form->getData();//construit la categorie avec les données que j'ai spécifier
            $entityManager->persist($moduleformation);//creer l'objet stagiaire
            $entityManager->flush();// insert les element dans  les données dans la base
            
            return $this->redirectToRoute('app_module_formation');
        }
        return $this->render('/module_formation/addedit.html.twig', [
            'formAddModuleFormation' => $form->createView(),
            'editMode'=> $moduleFormation->getId()!==null

        ]);
    }
  
     /**
      * @Route("/moduleformation/{id}/remove", name="remove_module_formation")
      */
    public function removeModuleFormation(ModuleFormation $moduleFormation= null, ManagerRegistry $doctrine): Response{
            $entityManager = $doctrine->getManager();
            $entityManager->remove($moduleFormation);
            $entityManager->flush();
            return $this->redirectToRoute('app_module_formation');
    } 

   /**
     * @Route("/moduleformation/{id}", name="show_module_formation")
     */
    public function show(ModuleFormation $moduleFormation): Response
    {
        return $this->render('module_formation/show.html.twig', [
            'moduleformation' => $moduleFormation,
        ]);
    }


}
