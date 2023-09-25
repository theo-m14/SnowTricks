<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TricksRepository $tricksRepository): Response
    {
        if($this->getUser() && !$this->getUser()->isVerified()){
            $this->addFlash('unverified',"Veuillez vérifiez votre adresse mail");
        }

        $tricks = $tricksRepository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController','tricks'=> $tricks
        ]);
    }
}
