<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Définition du controller MeController qui permet de récupérer les données de l'utilisateur connecté
final class MeController extends AbstractController
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function __invoke(): UserInterface
    {
        /** @var User $user */
        // Récupération des données de l'utilisateur connecté avec la méthode getUser() de l'interface TokenStorageInterface
        // qui retourne l'utilisateur connecté à partir du son token de connexion
        $user = $this->tokenStorage->getToken()->getUser();

        return $user;
    }
}
