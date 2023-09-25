<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\TricksVideo;
use App\Form\TricksFormType;
use App\Repository\TricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksController extends AbstractController
{
    #[Route('/ajoutTricks', name: 'app_add_tricks')]
    public function add(Request $request,EntityManagerInterface $entityManager): Response
    { 
        if (!$this->getUser() || !$this->getUser()->isVerified()) {
            $this->addFlash('error', 'Vous devez posséder un compte et être vérifié pour cela');
            //return $this->redirectToRoute('app_login');
        }

        $tricks = new Tricks();
        $tricks->addTricksVideo(new TricksVideo());

        $form = $this->createForm(TricksFormType::class, $tricks);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $tricks->setUser($this->getUser());
            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success','Tricks ajouté!');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('tricks/form.html.twig', [
            'form' => $form->createView(),
            'tricks' => $tricks
        ]);
    }
}