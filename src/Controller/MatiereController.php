<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatiereController extends AbstractController
{
    // ajouter
    #[Route('/matieres', name: 'app_matiere')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($matiere);
            $em->flush();
        }

        $matieres = $em->getRepository(Matiere::class)->findAll();

        return $this->render('matiere/index.html.twig', [
            'matieres' => $matieres,
            'ajout' => $form->createView(),
        ]);
    }

    // modifier
    #[Route('/matiere/{id}', name: 'matiere')]
    public function matiere(Matiere $matiere= null, EntityManagerInterface $em, Request $request): Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($matiere);
            $em->flush();
        }

        $matieres = $em->getRepository(Matiere::class)->findAll();

        return $this->render('matiere/matiere.html.twig', [
            'matieres' => $matieres,
            'ajout' => $form->createView(),
        ]);
    }
}
