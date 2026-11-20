<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;
use App\Entity\Job;
use App\Entity\Candidature;
use Symfony\Component\HttpFoundation\Request;

final class JobController extends AbstractController
{


    #[Route('/job', name: 'app_job')]
   public function createDemo(EntityManagerInterface $entityManager): Response
{
$job = new Job();
$job->setType('CDI');
$job->setCompany('EMKA Med Tunisie');
$job->setDescription('Technicien méthodes électronique');
$job->setEmail('recrutement@emkaelec.com');
$job->setExpiresAt(new \DateTimeImmutable('+30 days'));
$image=new Image();
$image->setUrl('https://cdn.pixabay.com/photo/2015/10/30/10/03/gold-1013618_960_720.jpg ');
$image->setAlt('job de reves');
$entityManager->persist($image);
$job->setImage($image);
//creation des candidats
$candidature1=new Candidature();
$candidature2=new Candidature();
//remplir candidat 1
$candidature1->setCandidat("Rhaiem");
$candidature1->setContenu("formation J2EE");
$candidature1->setDate(new \DateTime());
//remplir candidat 2
$candidature2->setCandidat("Salima");
$candidature2->setContenu("formation Symfony");
$candidature2->setDate(new \DateTime());
//affecter un Job Aux candidats
$candidature1->setJob($job);
$candidature2->setJob($job);
$entityManager->persist($job);
$entityManager->persist($candidature1);
$entityManager->persist($candidature2);
$entityManager->flush();
return $this->render('job/index.html.twig', [
'id' => $job->getId(),
]);
}

#[Route('/job/{id}', name: 'job_show')]
public function show(EntityManagerInterface $entityManager, int $id): Response
{
$job = $entityManager->getRepository(Job::class)->find($id);
if (!$job) {
throw $this->createNotFoundException('Job introuvable');
}
$listCandidatures=$entityManager->getRepository(Candidature::class)->findBy(['Job'=>$job]);
return $this->render('job/show.html.twig', [
'job' => $job,
'listCandidatures' => $listCandidatures,
]);
}
#[Route("/add",name: "ajout_job")]
public function ajouter2(Request $request, EntityManagerInterface $em)
{
$job = new Job();
$form = $this->createForm("App\Form\JobType",$job);
$form -> handleRequest($request);
if ($form->isSubmitted()) {
$em->persist($job);
$em->flush();
return $this->redirectToRoute('accueil');
}
return $this->render('job/ajouter.html.twig',
['f'=>$form->createView()]);
}




#[Route ("/listejob",name:"listejob")]
public function listejob(EntityManagerInterface $em){
$repo = $em->getRepository(Job::class);
$lesjobs = $repo->findAll();
// lancer la recherche quand on clique sur le bouton
return $this->render('job/home.html.twig',
['lesjobs' => $lesjobs]);
}
}
