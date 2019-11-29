<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Talk;
use App\Service\ConnectEventsReader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 *
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/{id<\d+>}", name="event_details", methods={"GET"})
     * @ParamConverter("event")
     */
    public function details(Event $event, ConnectEventsReader $reader, TokenStorageInterface $tokenStorage): Response
    {
        if (!$event->isOnline()) {
            throw $this->createNotFoundException();
        }

        // Only User who attended can see details
        $user = $tokenStorage->getToken()->getApiUser();
        if (!$reader->checkUserAttendedEvent($user, $event)) {
            return $this->redirectToRoute('home');
        }

        return $this->render('event/details.html.twig', [
            'event' => $event,
            // TODO: display mean grade for each talk
        ]);
    }

    /**
     * @Route("/talk/{id<\d+>}", name="talk_details", methods={"GET"})
     * @ParamConverter("talk")
     */
    public function talk(Talk $talk, TokenStorageInterface $tokenStorage, ConnectEventsReader $reader): Response
    {
        if (!$talk->getEvent()->isOnline()) {
            throw $this->createNotFoundException();
        }

        // Only User who attended can see details
        $user = $tokenStorage->getToken()->getApiUser();
        if (!$reader->checkUserAttendedEvent($user, $talk->getEvent())) {
            return $this->redirectToRoute('home');
        }

        return $this->render('event/talk.html.twig', [
            'talk' => $talk,
            // TODO: display mean grade
        ]);
    }
}
