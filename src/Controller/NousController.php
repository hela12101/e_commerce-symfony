<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NousController extends AbstractController
{
    /**
     * @Route("/nous", name="nous")
     */

    public function index(): Response
    {
        return $this->render('nous/index.html.twig', [
            'controller_name' => 'NousController',
        ]);
    }
}