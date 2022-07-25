<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;

class OrderController extends AbstractController
{
    private $entityManager;//doctrine msaminha aka
    public function __construct(EntityManagerInterface  $entityManager){
        $this->entityManager=$entityManager;
    }

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart,Request $request)
    {
        if (!$this->getUser()->getAdresses()->getValues()){
            return $this->redirectToRoute('account_add_address') ;
        }

        $form=$this->createForm(OrderType::class,null,[
            'user'=>$this->getUser()
        ]);

        return $this->render('order/index.html.twig',[
            'form'=>$form->createView(),
            'cart'=>$cart->getfull()
        ]);
    }

    /**
     * @Route("/commande/recap", name="order_recap",methods={"POST"})
     */
    public function add(Cart $cart,Request $request)
    {
       









        $form=$this->createForm(OrderType::class,null,[
            'user'=>$this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){
            $date = new \DateTimeImmutable();
            $carriers=$form->get('carriers')->getData();
            $delivery=$form->get('adresses')->getData();
            $delivery_content=$delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .='<br/>'.$delivery->getPhone();

            if ($delivery->getCompany()){
                $delivery_content .='<br/>'.$delivery->getCompany();
            }

            $delivery_content .='<br/>'.$delivery->getAddress();
            $delivery_content .='<br/>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .='<br/>'.$delivery->getPays();




          //enregistrer mes commandes order()
            $order= new Order();
            $reference=$date->format('dmy').'-'.uniqid();
            $order->setReference( $reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setState(0);

            $this->entityManager->persist($order);

            //enregistrer mes pdts

            foreach ($cart->getFull() as $product){
                $orderDetails= new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']/100);
                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();

            return $this->render('order/add.html.twig',[
                'cart'=>$cart->getfull(),
                'carrier'=>$carriers,
                'delivery'=>$delivery_content
            ]);
        }
        return $this->redirectToRoute('cart');


    }
}
