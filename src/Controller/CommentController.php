<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/comment')]
class CommentController extends AbstractController
{

    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/editComment', name: 'app_comment_edit', methods: ['PUT'])]
    public function edit(Request $request, CommentRepository $commentRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $commentId = $request->request->get('comment-id');

        $commentContent = $request->request->get('content');

        if ( strlen($commentContent) < 10 ) {
            return new JsonResponse(['error' => 'Votre commentaire doit faire plus de 10 caractères'], Response::HTTP_BAD_REQUEST);
        }

        $comment = $commentRepository->find(intval($commentId));

        //Si le commentaire est nul ou si l'utilisateur actuel n'est pas l'auteur du commentaire
        if($comment === null || $this->getUser() != $comment->getUser())
        {
            return new JsonResponse([], Response::HTTP_FORBIDDEN);
        }

        $comment->setContent($commentContent);

        $entityManager->persist($comment);

        $entityManager->flush();

        return new JsonResponse([['success' => 'Commentaire modifié']]);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['DELETE'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $tricks = $comment->getTrick();

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tricks_readOne', ['id' => $tricks->getId()], Response::HTTP_SEE_OTHER);
    }
}
