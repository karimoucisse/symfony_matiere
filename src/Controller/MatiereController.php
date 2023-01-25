<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MatiereController extends AbstractController
{
    // ajouter
    #[Route('/matieres', name: 'app_matiere')]
    public function index(EntityManagerInterface $em, Request $request, TranslatorInterface $t): Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($matiere);
            $em->flush();
            $this->addFlash('success', $t->trans('matiere.ajoutee'));
        }

        $matieres = $em->getRepository(Matiere::class)->findAll();

        return $this->render('matiere/index.html.twig', [
            'matieres' => $matieres,
            'ajout' => $form->createView(),
        ]);
    }

    // modifier
    #[Route('/matiere/{id}', name: 'matiere')]
    public function matiere(Matiere $matiere= null, EntityManagerInterface $em, Request $request, TranslatorInterface $t): Response
    {
        if($matiere == null){
            $this->addFlash('error', $t->trans('matiere.introuvable'));
        }

        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($matiere);
            $em->flush();
            return $this->redirectToRoute('app_matiere');
        }

        return $this->render('matiere/matiere.html.twig', [
            'matiere' => $matiere,
            'ajout' => $form->createView(),
        ]);
    }

    // supprimer
    #[Route('/matiere/supprimer/{id}', name: 'matiere_supprimer')]
    public function matiereSupprimer(Matiere $matiere= null, EntityManagerInterface $em, TranslatorInterface $t): Response
    {
        if($matiere == null){
            $this->addFlash('error', $t->trans('matiere.introuvable'));
        }else {
            $em->remove($matiere);
            $em->flush();
        }
        return $this->redirectToRoute('app_matiere');

    }
}
