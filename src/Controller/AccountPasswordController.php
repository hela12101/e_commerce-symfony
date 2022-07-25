<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
//use http\Env\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{ private $entityManager;//doctrine msaminha aka
    public function __construct(EntityManagerInterface  $entityManager){
        $this->entityManager=$entityManager;
    }

    /**
     * @Route("/compte/mdp", name="account_password")
     */
    public function index(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $notification=null;

        $user=$this->getUser();
        $form = $this->createForm(ChangePasswordType::class,$user);

        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $old_pwd=$form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user,$old_pwd)){
                $new_pwd=$form->get('new_password')->getData();
                $password=$encoder->encodePassword($user,$new_pwd);

                $user->setPassword($password);

                $this->entityManager->flush(); //executer
                $notification="votre mot de passe a bien été mis à jour.";

            }else{
                $notification="votre mot de passe n'est pas été mis à jour.";
            }

        }

        return $this->render('account/password.html.twig',[
            'form'=>$form->createview(),
            'notification'=>$notification
        ]);
    }
}
