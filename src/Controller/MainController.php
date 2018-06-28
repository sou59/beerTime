<?php
//on indique App à symfony qui redirige de lui même vers : src/Controller/MainController.php
namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//pour le routing par annotations
use Symfony\Component\Routing\Annotation\Route;

// page home accessible via : (BeerTime/public/index.php)

class MainController extends Controller
{
    /**
      * @Route("/", name="home")
    */

    public function home()
    {
        return $this->render('main/home.html.twig');
    }
}