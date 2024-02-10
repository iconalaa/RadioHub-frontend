<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function frontOffice(): Response
    {
        return $this->render('front.html.twig', []);
    }
    #[Route('/admin', name: 'app_admin')]
    public function backOffice(): Response
    {
        return $this->render('back.html.twig', []);
    }
}
