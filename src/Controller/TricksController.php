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
    #[Route('/', name: 'app_home')]
    public function index(TricksRepository $tricksRepository): Response
    {
        if($this->getUser() && !$this->getUser()->isVerified()){
            $this->addFlash('unverified',"Veuillez vérifiez votre adresse mail");
        }

        $tricks = $tricksRepository->findAll();

        return $this->render('tricks/index.html.twig', ['tricks'=> $tricks]);
    }

    #[Route('/tricks/{id}', name: 'app_tricks_readOne')]
    public function getOne(Tricks $trick, int $id) : Response
     {
        return $this->render('tricks/readOne.html.twig', ['trick'=> $trick]);
     }

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

    #[Route('/editTricks/{id}', name: 'app_edit_tricks')]
    public function edit(Tricks $tricks,Request $request,EntityManagerInterface $entityManager): Response
    { 
        if (!$this->getUser() || !$this->getUser()->isVerified()) {
            $this->addFlash('error', 'Vous devez posséder un compte et être vérifié pour cela');
            return $this->redirectToRoute('app_home');
        }

        if($tricks->getUser() !== $this->getUser()){
            $this->addFlash('error', 'Le tricks doit vous appartenir pour cela');
        }

        $form = $this->createForm(TricksFormType::class, $tricks);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $images = $tricks->getTricksImages();
            $dump = [];
            foreach ($images as $image) {
                $data = ['name' => $image->getName(),'file' => $image->getFile()];
                array_push($dump,$data);
            }
            var_dump($dump);
            // die();
            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success','Tricks ajouté!');
            //return $this->redirectToRoute('app_home');
        }

        return $this->render('tricks/form.html.twig', [
            'form' => $form->createView(),
            'tricks' => $tricks
        ]);
    }
}