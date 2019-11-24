<?php

namespace App\Controller;

use App\Service\ConnectEventsReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TokenStorageInterface $tokenStorage, ConnectEventsReader $reader)
    {
        $events = [];

        if ($tokenStorage->getToken() && $user = $tokenStorage->getToken()->getApiUser()) {
            $events = $reader->getOnlineUserAttended($user);
        }

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'events' => $events,
        ]);
    }
}
