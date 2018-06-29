<?php
// on indique App à symfony qui redirige de lui même vers : src/Controller/EventController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//pour le routing par annotations
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EventService;


class EventController extends Controller
{
    /**
      * @Route("/event/create", name = "event_create")
    */
    public function create()
    {
        return $this->render('event/create.html.twig');
    }

    // on met /event/{id} au lieu de /event/show pour accéder à l'id de l'événement à montrer
    // d+ signifie digit positif = l'id doit être un entier positif;

    /**
      * @Route("/event/{id}", name = "event_show", requirements={"id"="\d+"})
    */
    public function show( EventService $eventService, $id) 
    {
        $event = $eventService->getOne($id);
        if($event != false) {
            return $this->render('event/show.html.twig', array(
                'event'=> $event
            ));
        }
        return new Response("Cette page n\'éxiste pas", 404);
    }

    //pour l'url, on ne met pas /event/list mais juste /event 
    /**
      * @Route("/event", name = "event_list")
    */
    public function list( EventService $eventService) 
    {
        return $this->render('event/event.html.twig', array(
            'events'=> $eventService->getAll()
        ));
    }

    /**
      * @Route("/event/{id}/join", name = "event_join", requirements={"id"="\d+"})
    */
    public function join($id)
    {
        return $this->render('event/join.html.twig');
    }

    /**
      * @Route("/nedd-a-beer", name = "event_random", requirements={"id"="\d+"})
    */

    public function random(EventService $eventService )
    {
        // fonction idem que show et en plus on attend ici le même template puisque le template ne fait pas de traitement de données mais affiche juste un élément
        $event = $eventService->getRandom();
        if($event != false) {
            return $this->render('event/show.html.twig', array(
                'event'=> $event
            ));
        }
        return new Response("Cette page n\'éxiste pas", 404);
    }



}