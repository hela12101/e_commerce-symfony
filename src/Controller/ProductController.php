<?php

namespace App\Controller;
use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $entityManager;//doctrine msaminha aka
    public function __construct(EntityManagerInterface  $entityManager){
        $this->entityManager=$entityManager;
    }
    /**
     * @Route("/produit", name="product")
     */

    public function index(Request $request )
    {
        $product=$this->entityManager->getRepository(Product::class)->findAll();
        $search=new Search();
        $form=$this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $product=$this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }

        return $this->render('product/index.html.twig',[
            'product'=>$product,
            'form' =>$form->createView()
        ]);
    }
    /**
     * @Route("/produits/{slog}", name="produit")
     */

    public function show($slog)
    {
        $products=$this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $produit=$this->entityManager->getRepository(Product::class)->findOneByslog($slog);
        if (!$produit){
            return $this->redirectToRoute('product');
        }

        return $this->render('product/show.html.twig',[
            'produit'=>$produit,
            'products'=>$products
        ]);
    }
}
