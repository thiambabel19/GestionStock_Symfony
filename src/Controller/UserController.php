<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/User/liste', name: 'gesutilisateur')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        
        // $u = new User();
        // $form = $this->createForm(UserType::class, $u, array('action' => $this->generateUrl('ajoututilisateur')));
        // $data['form'] = $form->createView();
        $data['utilisateurs'] = $em->getRepository(User::class)->findAll();
        return $this->render('registration/liste.html.twig', $data);
    }

    // #[Route('/User/ajout', name: 'ajoututilisateur')]
    // public function ajout(Request $request, ManagerRegistry $doctrine):Response
    // {
    //     $us = new User();
    //     $form = $this->createForm(UserType::class, $us, array('action' => $this->generateUrl('ajoututilisateur')));
        
    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $us = $form->getData();
    //         $em = $doctrine->getManager();
    //         $em->persist($us);
    //         $em->flush();
            
    //     }
        
    //     return $this->redirectToRoute('gesutilisateur');
    // }
}