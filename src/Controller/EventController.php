<?php
// on indique App à symfony qui redirige de lui même vers : src/Controller/EventController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//pour le routing par annotations
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Tests\Templating\Helper\RequestHelperTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
// use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use App\Service\EventService;
use App\Form\FormType;
use App\Entity\Event;
use App\Entity\User;
use App\Service\FileUploader;
use App\Repository\EventRepository;


class EventController extends Controller
{
    // Page rejoindre un évènement      
    /**
      * @Route("/event/{id}/join", name = "event_join", requirements={"id"="\d+"})
    */
    public function join($id)
    {
        return $this->render('event/join.html.twig');
    }
   
    // Page création d'un évènement
    /**
      * @Route("/event/create", name = "event_create")
    */
    public function create( Request $request, FileUploader $fileUploader, EventRepository $eventRepository)
    {
        $event = new Event();
        $form = $this->createForm(FormType::class, $event);

        // on interception des données : hydratation, coincider le formulaire avec les donnees reçu en post et get
        $form->handleRequest($request);
        // vérification de la validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            if( !empty($event->getPosterFile()) ){
                // gérer l'upload des posters
                $file = $event->getPoster();
               // dump( $file );exit();
                $fileName = $fileUploader->upload($file);
               // dump( $fileName );
                //dump( $event );
            }else {
                $fileName = $event->getPosterURL();
            }

            $event->setPoster($fileName);
            
            // sauvegarder l'entité
            // appel doctrine Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // temporaire user au hazard
            $owner = $em->getRepository(User::class)->findOneBy([]);
            $event->setOwner($owner);

            // attache à doctrine l'objet (persistance de l'entité)
            // on ne le fait pas pour des modif sur un objet existant
            $em->persist($event);

            // envoie des modif à la bdd 
            $em->flush();

            // message flash
            $this->addFlash(
                'success',
                'Votre évènempent a bien été créer'
            );

            // redirige sur la page d'accueil après la soummision du formulaire
            return $this->redirectToRoute('event_list');
        }
        return $this->render('event/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    // page voir le détail d'un évènement
    /**
      * @Route("/event/{id}", name = "event_show", requirements={"id"="\d+"})
    */
     // on met /event/{id} au lieu de /event/show pour accéder à l'id de l'événement à montrer
    // d+ signifie digit positif = l'id doit être un entier positif;
    public function show( EventService $eventService, $id) 
    {
        $event = $eventService->getOne($id);
        if(!empty($event)){
            return $this->render('event/show.html.twig', array(
                'event'=> $event
            ));
        }
        return new Response("Cette page n\'éxiste pas", 404);
    }

    
    // Voir tous les évènements
    //pour l'url, on ne met pas /event/list mais juste /event 
    /**
      * @Route("/event", name = "event_list")
    */
    public function list( EventService $eventService, Request $request) 
    {
        // Request $request mis en paramètre donc inutile de noter :
        // $request = Request::createFromGlobals();

        $search = $request->query->get('search');
        
        // Si request->get('search') est rempli
        // alors return render 
        if(!empty($search)) {
            return $this->render('event/event.html.twig', array(
                'events'=> $eventService->getByName($search),
                'future' => $eventService->countFutureEvents(),
            ));
        }
        
        $sort = !empty($request->query->get('sort')) ? $request->query->get('sort') : 'id';     

        return $this->render('event/event.html.twig', array(
            'events'=> $eventService->getAll($sort),
            'future' => $eventService->countFutureEvents(),
        ));   
    }


    //page need a beer
    /**
      * @Route("/nedd-a-beer", name = "event_random", requirements={"id"="\d+"})
    */
    public function random(EventService $eventService )
    {
        // fonction idem que show et en plus on attend ici le même template
        // puisque le template ne fait pas de traitement de données mais affiche juste un élément
        $event = $eventService->getRandom();
        if($event != false) {
            return $this->render('event/show.html.twig', array(
                'event'=> $event
            ));
        }
        return new Response("Cette page n\'éxiste pas", 404);
    }

}