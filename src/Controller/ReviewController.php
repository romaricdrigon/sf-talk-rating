<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventReview;
use App\Entity\SfConnectUser;
use App\Entity\Talk;
use App\Entity\TalkReview;
use App\Form\EventReviewType;
use App\Form\TalkReviewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 *
 * @Route("/review")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/{id}/event", name="review_event", methods={"GET", "POST"})
     * @ParamConverter("event")
     */
    public function reviewEvent(Event $event, TokenStorageInterface $tokenStorage, Request $request): Response
    {
        if (!$event->isOnline()) {
            throw $this->createNotFoundException();
        }

        $user = $tokenStorage->getToken()->getApiUser();

        $review = new EventReview($event, SfConnectUser::buildFromApiUser($user));
        $form = $this->createForm(EventReviewType::class, $review);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($review);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Thank you for your review! It will be published soon.');

            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/event.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/talk", name="review_talk", methods={"GET", "POST"})
     * @ParamConverter("talk")
     */
    public function reviewTalk(Talk $talk, TokenStorageInterface $tokenStorage, Request $request): Response
    {
        if (!$talk->getEvent()->isOnline()) {
            throw $this->createNotFoundException();
        }

        $user = $tokenStorage->getToken()->getApiUser();

        $talkReview = new TalkReview($talk, SfConnectUser::buildFromApiUser($user));
        $form = $this->createForm(TalkReviewType::class, $talkReview);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($talkReview);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Thank you for your review! It will be published soon.');

            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/talk.html.twig', [
            'talk' => $talk,
            'form' => $form->createView(),
        ]);
    }
}
