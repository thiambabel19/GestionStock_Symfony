<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\Produit;
use App\Form\SortieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SortieController extends AbstractController
{
    #[Route('/Sortie/liste', name: 'gessortie')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        
        $s = new Sortie();
        $form = $this->createForm(SortieType::class, $s, array('action' => $this->generateUrl('ajoutsortie')));
        $data['form'] = $form->createView();
        $data['sorties'] = $em->getRepository(Sortie::class)->findAll();
        return $this->render('sortie/sortie.html.twig', $data);
    }

    #[Route('/Sortie/ajout', name: 'ajoutsortie')]
    public function ajout(Request $request, ManagerRegistry $doctrine):Response
    {
        $s = new Sortie();

        $form = $this->createForm(SortieType::class, $s, array('action' => $this->generateUrl('ajoutsortie')));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $s = $form->getData();
            $s->setUser($this->getUser());
            $em = $doctrine->getManager();
            
            $p = $em->getRepository(Produit::class)->find($s->getProduit()->getId());
            $qsortie = $s->getQteSortie();
            
            if($p->getStock() < $s->getQteSortie()){
                $s = new Sortie();
                $form = $this->createForm(SortieType::class, $s, array('action' => $this->generateUrl('ajoutsortie')));
                $data['form'] = $form->createView();
                $data['sorties'] = $em->getRepository(Sortie::class)->findAll();
                $data['error_message'] = "Le stock disponible est inférieur à ".$qsortie;

                return $this->render('sortie/sortie.html.twig', $data);
            }else{
                $em->persist($s);
                $em->flush();
                //mise à jour des stocks after sortie produit
                $stock = $p->getStock() - $s->getQteSortie();
                $p->setStock($stock);
                $em->flush();
                return $this->redirectToRoute('gessortie');
            }
        }
    }
}