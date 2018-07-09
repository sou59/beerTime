<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Service\EventService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Tests\Templating\Helper\RequestHelperTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use App\Entity\User;
use App\Form\RegistrationFormType;
// use App\Service\User;


class UserController extends Controller
{
    
    // Login      
    /**
      * @Route("/user", name = "user_login")
    */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
       // $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
       // $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'lastUsername' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
        // return $this->render('event/login.html.twig');
    }

    // /**
    //  * @Route("/logout", name="logout")
    //  */
    // public function logout(): void
    // {
    //     throw new \Exception('This should never be reached!');
    // } Inutile ici car gérer pa securite.yaml et routes.yaml

    // S'inscrire      
    /**
      * @Route("/user/registration", name = "user_registration")
    */
    public function registration( Request $request, UserPasswordEncoderInterface $passwordEncoder ){

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        // on interception des données : hydratation, coincider le formulaire avec les donnees reçu en post et get
        $form->handleRequest($request);
        // vérification de la validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // ajouter mdp / encoder mdp et set mdp
            // Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->setRoles(array('ROLE-USER', 'ROLE_ADMIN'));

            // sauvegarder l'entité
            // appel doctrine Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // attache à doctrine l'objet (persistance de l'entité)
            // on ne le fait pas pour des modif sur un objet existant
            $em->persist($user);

            // envoie des modif à la bdd 
            $em->flush();

            // message flash
            $this->addFlash(
                'success',
                'Vous êtes bien enregistrés'
            );

            // redirige sur la page d'accueil après la soummision du formulaire
            return $this->redirectToRoute('event_list');
        }
         return $this->render('user/registration.html.twig', array(
             'form' =>$form->createView()
         ));
    }

}
