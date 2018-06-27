<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class EventController extends Controller {
    public function event(){
        return new Response('Evenement');

    }

}