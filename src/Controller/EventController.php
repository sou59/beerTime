<?php
// on indique App à symfony qui redirige de lui même vers : src/Controller/EventController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//pour le routing par annotations
use Symfony\Component\Routing\Annotation\Route;



class EventController extends Controller
{

    private $events = array(
        1 =>  array(
            'id' => 1,
            'name' => 'Bière Partie',
            'description' => 'Toutes les bières du monde !',
            'address' => '12 rue des Bouleaux',
            'city' => 'Bailleul',
            'zip' => '59300',
            'country' => 'France',
            'capacity' => "200 places",
            'start_at' => '20-06-2018 06:20:30',
            'end_at' => '30-06-2018 07:30:30',
            'price' => "20 €",
            'poster' => "Lienimage",
            'owner' => "Theddy",
            'voir' => 'Lien_id1'
        ),
        2 =>  array(
            'id' => 2,
            'name' => 'Fabious Partie',
            'description' => 'La meilleur teuf du monde',
            'address' => '12 rue des Bouleaux',
            'city' => 'Bailleul',
            'zip' => '59300',
            'country' => 'France',
            'capacity' => "200 places",
            'start_at' => '30-06-2018 06:20:30',
            'end_at' => '30-06-2018 07:30:30',
            'price' => "20 €",
            'poster' => "Lienimage",
            'owner' => "Theddy",
            'voir' => 'Lien_id1'
        ),
        3 =>  array(
            'id' => 3,
            'name' => 'Apéro Symfony',
            'description' => 'Si vous avez le temps...',
            'address' => '12 rue des Bouleaux',
            'city' => 'Bailleul',
            'zip' => '59300',
            'country' => 'France',
            'capacity' => "200 places",
            'start_at' => '28-06-2018 21:20:30',
            'end_at' => '30-06-2018 07:30:30',
            'price' => "20 €",
            'poster' => "Lienimage",
            'owner' => "Theddy",
            'voir' => 'Lien_id1'
        )
    );

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
    public function show($id)
    {
        return $this->render('event/show.html.twig', array(
            'event'=> $this->events[$id]
        ));
    }

    //pour l'url, on ne met pas /event/list mais juste /event 
    /**
      * @Route("/event", name = "event_list")
    */
    public function list()
    {
        return $this->render('event/event.html.twig', array(
            'events'=> $this->events
        ));
    }

    /**
      * @Route("/event/{id}/join", name = "event_join", requirements={"id"="\d+"})
    */
    public function join($id)
    {
        return $this->render('event/join.html.twig');
    }

}