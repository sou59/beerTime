<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;


class EventService {
    private $events; 

    public function __construct() {
        $this->events = array(
            1 =>  array(
                'id' => 1,
                'name' => 'Bière Partie',
                'description' => 'Toutes les bières du monde !',
                'address' => '12 rue des Bouleaux',
                'city' => 'Bailleul',
                'zip' => '59300',
                'country' => 'be',
                'capacity' => "200 places",
                'start_at' => new \DateTime('2018-07-10 06:20:30'), // Format DateTime et enlever date() des templates
                'end_at' => new \DateTime('2018-07-20 07:30:30'),
                'price' => "20",
                'poster' => "https://madeinmarseille.net/actualites-marseille/2016/11/meilleur-bar-biere-artisanale-brasserie.jpg",
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
                'country' => 'fr',
                'capacity' => "200 places",
                'start_at' => new \DateTime('2018-05-10 06:20:30'),
                'end_at' => new \DateTime('2018-06-03 07:30:30'),
                'price' => "null",
                'poster' => "https://www.aucomptoirdesvins.fr/wp-content/uploads/2018/02/meilleure-tireuse-a-biere.jpg",
                'owner' => "Teddy",
                'voir' => 'Lien_id1'
            ),
            3 =>  array(
                'id' => 3,
                'name' => 'Apéro Symfony',
                'description' => 'Si vous avez le temps...',
                'address' => '12 rue des Bouleaux',
                'city' => 'Bailleul',
                'zip' => '59300',
                'country' => 'fr',
                'capacity' => "200 places",
                'start_at' => new \DateTime('2018-06-28 21:20:30'),
                'end_at' => new \DateTime('2018-06-30 07:30:30'),
                'price' => "50",
                'poster' => "http://images-mds.staticskynet.be/NewsFolder/original/apero-leffen_20140506104538.jpg",
                'owner' => "Theddy",
                'voir' => 'Lien_id1'
            ),
            4 => array(
                'id' => 4,
                'name' => 'La grosse biture',
                'description' => '3 jours de beuverie non stop.',
                'address' => '62 avenue des Champs-Elysées',
                'city' => 'Paris',
                'zip' => '75100',
                'capacity' => 15000,
                'start_at' => new \DateTime('2018-07-04 19:00:00'),
                'end_at' => new \DateTime('2018-07-25 10:00:00'),
                'country' => 'FR',
                'price' => 100,
                'poster' => 'https://www.aperodujeudi.com/wp-content/uploads/2016/01/spa-biere-republique-tcheque.jpg',
                'owner' => 'Webforce3',
            )
        );

    }
    public function getAll() {
        return $this->events;
    }

    public function getOne($id)
    {
        foreach ( $this->events as $event) {
            if($event['id'] == $id) {
                return $event;
            }
        }
        return false;
    }

    public function getRandom()
    {
        // copier le tableau dans un tableau temporaire pour ne pas toucher au tab d'origine
        $events = $this->events;
        
        // on fait le mélange des évènements
        shuffle($events);
        $now = new \DateTime();

        foreach ($events as $event) {
            if ($event['start_at'] <= $now && $event['end_at'] > $now) {
                return $event;
            }
        }
        return false;


        
    }



}

