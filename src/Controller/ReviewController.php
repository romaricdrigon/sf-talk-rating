<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventReview;
use App\Entity\SfConnectUser;
use App\Entity\Talk;
use App\Entity\TalkReview;
use App\Form\TalkReviewType;
use App\Service\ConnectEventsReader;
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
     * @Route("/{id}/talk", name="review_talk", methods={"GET", "POST"})
     * @ParamConverter("talk")
     */
    public function reviewTalk(Talk $talk, TokenStorageInterface $tokenStorage, Request $request, ConnectEventsReader $reader): Response
    {
        if (!$talk->getEvent()->isOnline()) {
            throw $this->createNotFoundException();
        }
        if (!$talk->canBeReviewed($tokenStorage->getToken()->getApiUser()->get('uuid'))) {
            return $this->redirectToRoute('talk_details', ['id' => $talk->getId()]);
        }

        $user = $tokenStorage->getToken()->getApiUser();

        // Only User who attended can review
        if (!$reader->checkUserAttendedEvent($user, $talk->getEvent())) {
            return $this->redirectToRoute('home');
        }

        $talkReview = new TalkReview($talk, SfConnectUser::buildFromApiUser($user));
        $form = $this->createForm(TalkReviewType::class, $talkReview);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($talkReview);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Thank you for your comment! It will be published soon.');

            return $this->redirectToRoute('talk_details', ['id' => $talk->getId()]);
        }

        return $this->render('review/talk.html.twig', [
            'talk' => $talk,
            'form' => $form->createView(),
        ]);
    }
}
