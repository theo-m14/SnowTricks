<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\TricksVideo;
use App\Form\TricksFormType;
use App\Repository\CommentRepository;
use App\Repository\TricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TricksImageRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class TricksController extends AbstractController
{
    #[Route('/', name: 'app_home', methods:['GET'])]
    public function index(TricksRepository $tricksRepository,Request $request): Response
    {   
        /** @var \App\Entity\User | null $user */
        $user = $this->getUser();

        if ($user && !$user->isVerified()) {
            $this->addFlash('unverified', "Veuillez vérifiez votre adresse mail");
        }

        $page = max(1, $request->query->getInt('page', 1));
        $paginator = $tricksRepository->getTricksPaginator($page);

        return $this->render('tricks/index.html.twig', [
            'tricks' => $paginator,
            'previous' => $page - 1,
            'next' => min(count($paginator), $page + 1),
            'tricks_per_page' => $tricksRepository::PAGINATOR_PER_PAGE
        ]);
    }

    #[Route('/tricks/{id}', name: 'app_tricks_readOne', methods:['GET','POST'])]
    public function getOne(Tricks $trick, int $id,Request $request,EntityManagerInterface $entityManager,CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        
        if(!$trick)
        {
            $this->addFlash('error',"Ce tricks n'existe pas");
            return $this->redirectToRoute('app_home');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setTrick($trick);
            $comment->setDate(new DateTimeImmutable());
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success','Commentaire ajouté');
            return $this->redirectToRoute('app_tricks_readOne',['id' => $trick->getId()]);
        }

        $page = max(1, $request->query->getInt('page', 1));
        $paginator = $commentRepository->getCommentPaginator($trick, $page);

        return $this->render('tricks/readOne.html.twig', [
            'trick' => $trick,
            'form'=> $form,
            'comments' => $paginator,
            'next' => min(count($paginator), $page + 1),
            'comment_per_page' => $commentRepository::PAGINATOR_PER_PAGE,
            'singleTrickPage' => true
        ]);
    }

    #[Route('/ajoutTricks', name: 'app_add_tricks', methods:['GET','POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user || !$user->isVerified()) {
            $this->addFlash('error', 'Vous devez posséder un compte et être vérifié pour cela');
            return $this->redirectToRoute('app_home');
        }

        $tricks = new Tricks();

        $form = $this->createForm(TricksFormType::class, $tricks);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tricks->setUser($this->getUser());
            if(!$tricks->getUpdatedAt()){
                $tricks->setUpdatedAt(new DateTimeImmutable());
            }
            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success', 'Tricks ajouté!');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('tricks/form.html.twig', [
            'form' => $form->createView(),
            'tricks' => $tricks,
            'singleTrickPage' => true
        ]);
    }

    #[Route('/editTricks/{id}', name: 'app_edit_tricks', methods:['POST','PUT','GET'])]
    public function edit(Tricks $tricks, Request $request, EntityManagerInterface $entityManager,TricksImageRepository $tricksImageRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user || !$user->isVerified()) {
            $this->addFlash('error', 'Vous devez posséder un compte et être vérifié pour cela');
            return $this->redirectToRoute('app_home');
        }

        if ($tricks->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Le tricks doit vous appartenir pour cela');
        }  

        $tricksImages = $tricksImageRepository->findBy(['trick' => $tricks->getId()]);

        foreach($tricksImages as $image){
            $tricks->addTricksImage($image);
        }

        $form = $this->createForm(TricksFormType::class, $tricks);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success', 'Tricks modifié!');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('tricks/form.html.twig', [
            'form' => $form->createView(),
            'tricks' => $tricks,
            'singleTrickPage' => true
        ]);
    }

    #[Route('/deleteTricks/{id}', name : "app_tricks_delete", methods:['GET','POST'])]
    public function delete(Tricks $tricks,Request $request,EntityManagerInterface $entityManager) : Response
    {
        if ($tricks->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Vous devez être propriétaire du tricks pour le supprimer');
            return $this->redirectToRoute('app_home');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-tricks', $submittedToken)) {

            foreach ($tricks->getComments() as $comment) {
                $entityManager->remove($comment);
            }
            $entityManager->remove($tricks);
            $entityManager->flush();
            $this->addFlash('success','Tricks supprimé');
            return $this->redirectToRoute('app_home');
        }

        $this->addFlash('error', 'Vous devez être propriétaire du serveur pour le supprimer');
        return $this->redirectToRoute('app_home');
    }
    
    #[Route('/ajaxTricks', name: 'app_tricks_json', methods:['GET'])]
    public function getTricksJson(TricksRepository $tricksRepository,Request $request) : JsonResponse
    {   
        $page = max(1, $request->query->getInt('page', 1));
        $paginator = $tricksRepository->getTricksPaginator($page);

        return new JsonResponse(['content' => $this->renderView('tricks/data.html.twig', [
            'tricks' => $paginator,
            'previous' => $page - 1,
            'next' => min(count($paginator), $page + 1),
            'tricks_per_page' => $tricksRepository::PAGINATOR_PER_PAGE
        ])]);
    }
}
