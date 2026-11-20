<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;
use App\Entity\Job;
final class CandidatureController extends AbstractController
{
    #[Route('/candidature', name: 'app_candidature')]
    public function index(): Response
    {
        return $this->render('candidature/index.html.twig', [
            'controller_name' => 'CandidatureController',
        ]);
    }


    #[Route("/Ajouter", name:"add_candidat")]
public function ajouter_cand(Request $request,EntityManagerInterface $em)
{
$candidat = new Candidature();
$fb = $this->createFormBuilder($candidat)
->add('candidat', TextType::class)
->add('contenu', TextType::class, array("label" => "Contenu"))
->add('date', DateType::class)
->add('job', EntityType::class, [
'class' => Job::class,
'choice_label' => 'type',
])
->add('Valider', SubmitType::class);
$form = $fb->getForm();

//injection dans la base de données
$form->handleRequest($request);
if ($form->isSubmitted()) {
$em->persist($candidat);
$em->flush();
return $this->redirectToRoute('home');
}
return $this->render('job/ajouter.html.twig',
['f' => $form->createView()]);
}

#[Route ("/",name:"home")]
public function home(EntityManagerInterface $em){
$repo = $em->getRepository(Candidature::class);
$lesCandidats = $repo->findAll();
// lancer la recherche quand on clique sur le bouton
return $this->render('candidature/home.html.twig',
['lesCandidats' => $lesCandidats]);
}


#[Route('/supp/{id}', name: 'cand_delete')]
public function delete(
    Request $request,
    $id,
    EntityManagerInterface $em
): Response {
    $c = $em->getRepository(Candidature::class)->find($id);

    if (!$c) {
        throw $this->createNotFoundException(
            'No candidature found for id ' . $id
        );
    }

    $em->remove($c);
    $em->flush();

    return $this->redirectToRoute('home');
}

}
