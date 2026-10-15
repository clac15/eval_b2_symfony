<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Event;
use App\Form\NewEventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class EventController extends AbstractController
{
    #[Route('/evenement/ajouter', name: 'app_event_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Event();
        $formulaire = $this->createForm(NewEventType::class, $evenement);
        $formulaire->handleRequest($request);
        
        

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $fichierImage = $formulaire->get('Image')->getData();
            $fichierImage->move('images', $fichierImage->getClientOriginalName());
            $evenement->setImage($fichierImage->getClientOriginalName());

            $evenement->setStatut(0); //0 pour non validé, 1 pour validé
            $entityManager->persist($evenement);
            $entityManager->flush(); 
            }

            
        return $this->render('event/new.html.twig', ['form' => $formulaire]);
    }

    #[Route('/evenement/{id}', name: 'app_event_show', requirements: ['id' => '\d+'])]
    public function show(): Response
    {
        return $this->render('event/show.html.twig');
    }
}
