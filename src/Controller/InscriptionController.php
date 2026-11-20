<?php
// src/Controller/InscriptionController.php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route('/Accueil', name: 'accueil')]
    public function number(): Response
    {
        $number = 59;

        return $this->render('Inscription/accueil.html.twig', [
            'number' => $number,
        ]);
    }
    #[Route('/voir/{id}', name: 'voir', requirements: ['id' => '\d+'])]
public function voir(int $id): Response
{
return $this->render('inscription/voir.html.twig', [
'id' => $id,
]);
}
}
