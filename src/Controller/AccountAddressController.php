<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Adresse;
use App\Form\AddressType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{     private $entityManager;//doctrine msaminha aka
    public function __construct(EntityManagerInterface  $entityManager){
        $this->entityManager=$entityManager;
    }
    /**
     * @Route("/compte/adresses", name="account_address")
     */
    public function index()
    {

        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/compte/ajouter_adresses", name="account_add_address")
     */
    public function add(Cart $cart,Request $request)
    {   $adresse =new Adresse();
        $form=$this->createForm(AddressType::class,$adresse);

        $form -> handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $adresse->setUser($this->getUser());
            $this->entityManager->persist($adresse);
            $this->entityManager->flush();
            if ($cart->get() ){
                return  $this->redirectToRoute('order');
            }else{
                return  $this->redirectToRoute('account_address');
            }
        }

        return $this->render('account/address_form.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/compte/modifier_adresses/{id}", name="account_edit_address")
     */
    public function edit( Request $request,$id)
    {   $adresse =$this->entityManager->getRepository(Adresse::class)->findOneById($id);
        if(!$adresse || $adresse->getUser() != $this->getUser() ){
            return $this->redirectToRoute('account_address');
        }
        $form=$this->createForm(AddressType::class,$adresse);

        $form -> handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $this->entityManager->flush();
            return  $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/compte/supprimer_adresses/{id}", name="account_delete_address")
     */
    public function delete($id)
    {   $adresse =$this->entityManager->getRepository(Adresse::class)->findOneById($id);

        if($adresse && $adresse->getUser() == $this->getUser() ){
            $this->entityManager->remove($adresse);
            $this->entityManager->flush();
        }



            return  $this->redirectToRoute('account_address');



    }
}
