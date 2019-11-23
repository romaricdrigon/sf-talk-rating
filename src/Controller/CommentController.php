<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventComment;
use App\Entity\SfConnectUser;
use App\Form\EventCommentType;
use App\Service\ConnectEventsReader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use SymfonyCorp\Connect\Api\Entity\User;

/**
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_index", methods={"GET"})
     */
    public function index(TokenStorageInterface $tokenStorage, ConnectEventsReader $reader): Response
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getApiUser();

        return $this->render('comment/index.html.twig', [
            'events' => $reader->getOnlineUserAttended($user),
            'username' => $user->get('username'),
        ]);
    }

    /**
     * @Route("/{id}/event", name="review_event", methods={"GET", "POST"})
     * @ParamConverter("event")
     */
    public function reviewEvent(Event $event, TokenStorageInterface $tokenStorage, Request $request): Response
    {
        $user = $tokenStorage->getToken()->getApiUser();

        $comment = new EventComment($event, SfConnectUser::buildFromApiUser($user));
        $form = $this->createForm(EventCommentType::class, $comment);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Thank you for your review! It will be published soon.');

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/event.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }
}
