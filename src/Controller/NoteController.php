<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Note;
use App\Form\NoteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class NoteController extends AbstractController
{
    #[Route('/', name: 'app_note')]
    public function index(EntityManagerInterface $em, Request $request, TranslatorInterface $t): Response
    {
        $note = new Note();
        $time = new \DateTimeImmutable();
        $time->format('D, d M Y H:i:s O');
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);
        $numerateur= 0;
        $denominateur= 0;
        $moyenne = 0;

        if($form->isSubmitted() && $form->isValid()){
            $note->setDate($time);
            $em->persist($note);
            $em->flush();
            $this->addFlash("success", $t->trans("note.ajoutee"));
        }

        $notes = $em->getRepository(Note::class)->findAll();
        $matieres = $em->getRepository(Matiere::class)->findAll();

        if($notes) {
            foreach($notes as $note){
                $numerateur += $note->getNote() * $note->getMatiere()->getCoefficient();
                $denominateur += $note->getMatiere()->getCoefficient();
            }

            if($numerateur != 0 && $denominateur !=0){
                $moyenne = round($numerateur/$denominateur, $precision = 1);
            }
        }
        
        return $this->render('note/index.html.twig', [
            "form"=>$form->createView(),
            "notes"=>$notes,
            "moyenne"=>$moyenne,
            "matieres"=>$matieres,
        ]);
    }
}
