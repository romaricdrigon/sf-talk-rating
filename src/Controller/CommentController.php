<?php

namespace App\Controller;

use App\Service\ConnectEventsReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use SymfonyCorp\Connect\Api\Entity\User;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment", methods={"GET"})
     */
    public function index(TokenStorageInterface $tokenStorage, ConnectEventsReader $reader)
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getApiUser();

        return $this->render('comment/index.html.twig', [
            'events' => $reader->getOnlineUserAttended($user),
            'username' => $user->get('username'),
        ]);
    }
}
