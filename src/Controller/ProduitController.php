<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/Produit/liste', name: 'gesproduit')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        
        $p = new Produit();
        $form = $this->createForm(ProduitType::class, $p, array('action' => $this->generateUrl('ajoutproduit')));
        $data['form'] = $form->createView();
        $data['produits'] = $em->getRepository(Produit::class)->findAll();
        return $this->render('produit/prod.html.twig', $data);
    }

    #[Route('/Produit/ajout', name: 'ajoutproduit')]
    public function ajout(Request $request, ManagerRegistry $doctrine):Response
    {
        $p = new Produit();

        $form = $this->createForm(ProduitType::class, $p, array('action' => $this->generateUrl('ajoutproduit')));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $p = $form->getData();
            $p->setUser($this->getUser());
            $em = $doctrine->getManager();
            $em->persist($p);
            $em->flush();
        }

        return $this->redirectToRoute('gesproduit');
    }
}