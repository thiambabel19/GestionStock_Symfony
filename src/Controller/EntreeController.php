<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Produit;
use App\Form\EntreeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntreeController extends AbstractController
{
    #[Route('/Entree/liste', name: 'gesentree')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        
        $e = new Entree();
        $form = $this->createForm(EntreeType::class, $e, array('action' => $this->generateUrl('ajoutentree')));
        $data['form'] = $form->createView();
        $data['entrees'] = $em->getRepository(Entree::class)->findAll();
        return $this->render('entree/entree.html.twig', $data);
    }

    #[Route('/Entree/ajout', name: 'ajoutentree')]
    public function ajout(Request $request, ManagerRegistry $doctrine):Response
    {
        $e = new Entree();

        $form = $this->createForm(EntreeType::class, $e, array('action' => $this->generateUrl('ajoutentree')));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $e = $form->getData();
            $e->setUser($this->getUser());
            $em = $doctrine->getManager();
            $em->persist($e);
            $em->flush();

            //mise à jour des stocks after achat
            $p = $em->getRepository(Produit::class)->find($e->getProduit()->getId());
            $stock = $p->getStock()+ $e->getQteEntree();
            $p->setStock($stock);
            $em->flush();
        }

        return $this->redirectToRoute('gesentree');
    }
}