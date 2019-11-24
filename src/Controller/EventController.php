<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 *
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/{id<\d+>}", name="event_details", methods={"GET"})
     */
    public function details(Event $event): Response
    {
        if (!$event->isOnline()) {
            throw $this->createNotFoundException();
        }

        return $this->render('event/details.html.twig', [
            'event' => $event,
            // TODO: display mean grade for each talk
        ]);
    }
}
