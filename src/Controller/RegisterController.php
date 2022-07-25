<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{       private $entityManager;//doctrine msaminha aka
    public function __construct(EntityManagerInterface  $entityManager){
        $this->entityManager=$entityManager;
    }
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $notification=null;

        $user=new User();
        $form=$this->createForm(RegisterType::class,$user);
        $form -> handleRequest($request);//formualire peut ecouter la requete
        if ($form->isSubmitted()&& $form->isValid()){
            $user=$form->getData(); //user ya5ou les donnes de formulaire

            //verifier s'il est deja inscrit
            $search_email= $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email)
            {
                $password=$encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);

                // $doctrine=$this->getDoctrine()->getManager();
                $this->entityManager->persist($user);
                $this->entityManager->flush(); //executer

                //envoyer un email au client
                $mail=new Mail();
                $content="Bienvenue".$user->getFirstname(). "<br/>Bonjour ".$user->getFirstname()." Merci d’avoir créé un compte sur Unique Meuble. Votre email pour se connecter  est ".$user->getEmail()." Vous pouvez accéder à l’espace membre de votre compte pour visualiser vos commandes, changer votre mot de passe.Au plaisir de vous revoir prochainement sur notre boutique..<br/>";


                $mail->send($user->getEmail(),$user->getFirstname(),'Bienvenue sur Unique Meuble',$content);

                $notification="Inscription effectué ";
            }else{
                $notification="Vous avez deja un compte";
            }
        }

        return $this->render('register/index.html.twig',[
            'form'=>$form->createView(),
            'notification'=>$notification
        ]);
    }
}
