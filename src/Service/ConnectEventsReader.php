<?php

namespace App\Service;

use App\Entity\Event;
use App\Repository\EventRepository;
use SymfonyCorp\Connect\Api\Entity\Badge;
use SymfonyCorp\Connect\Api\Entity\Index;
use SymfonyCorp\Connect\Api\Entity\User;

/**
 * ConnectEventsReader helps read events a SfConnect User attended.
 * At the moment, we look at badges.
 *
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class ConnectEventsReader
{
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param User $user User from SfConnect API
     * @return Event[] online events User attended
     */
    public function getOnlineUserAttended(User $user): array
    {
        /** @var Index $badges */
        $badges = $user->get('badges');

        if (!count($badges)) {
            return [];
        }

        $urls = [];

        /** @var Badge $badge */
        foreach ($badges->get('items') as $badge) {
            $urls[] = $badge->getSelfUrl();
        }

        return $this->eventRepository->findOnlineByBadgeUrl($urls);
    }
}
