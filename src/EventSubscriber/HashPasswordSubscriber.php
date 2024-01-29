<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

// Définition du subscriber HashPasswordSubscriber qui permet de hasher le mot de passe de l'utilisateur lors de sa création
class HashPasswordSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [KernelEvents::VIEW => ['preWrite', EventPriorities::PRE_WRITE]];
    }

    #[NoReturn]
    public function preWrite(ViewEvent $event): void
    {
        // Récupération de l'utilisateur créé
        $user = $event->getControllerResult();
        // Récupération de la méthode utilisée pour créer l'utilisateur
        $method = $event->getRequest()->getMethod();
        // Si l'utilisateur n'est pas une instance de User ou que la méthode utilisée n'est pas POST, on sort de la méthode
        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        // Hashage du mot de passe de l'utilisateur
        $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
    }
}
