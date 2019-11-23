<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use SymfonyCorp\Connect\Api\Entity\User;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index(TokenStorageInterface $tokenStorage)
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getApiUser();

        dump($user);

        return $this->render('comment/index.html.twig', [
            'username' => $user->get('username'),
        ]);
    }
}
