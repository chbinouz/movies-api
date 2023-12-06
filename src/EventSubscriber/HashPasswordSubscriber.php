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

class HashPasswordSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [KernelEvents::VIEW => ['pow', EventPriorities::PRE_WRITE]];
    }

    #[NoReturn]
    public function pow(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
    }
}
