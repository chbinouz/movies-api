<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Favori;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class favRankSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['postPersist', EventPriorities::POST_WRITE],
                ['prePersist', EventPriorities::PRE_WRITE],
            ],
        ];
    }

    public function postPersist(ViewEvent $event): void
    {
        $fav = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$fav instanceof Favori || $method !== Request::METHOD_PATCH) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        assert($user instanceof User);

        $favoriList = $user->getFavoris();

        foreach ($favoriList as $item) {
            if ($item->getRank() >= $fav->getRank() && $item !== $fav) {

                $item->setRank($item->getRank() + 1);
            }
        }

        $sortedList = $this->sortByRank($favoriList->toArray());

        $rank = 0;
        foreach ($sortedList as $data) {
            $data->setRank($rank);
            ++$rank;
        }

        $this->entityManager->flush();
    }

    private function sortByRank(array $array): array
    {
        usort($array, function ($a, $b) {
            return $a->getRank() <=> $b->getRank();
        });

        return $array;
    }

    public function prePersist(ViewEvent $event): void
    {
        $fav = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$fav instanceof Favori || $method !== Request::METHOD_POST) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        assert($user instanceof User);

        $favoriList = $user->getFavoris();

        $fav->setRank($favoriList->count());

        $this->entityManager->flush();
    }
}
