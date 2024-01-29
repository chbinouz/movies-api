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

// Définition du subscriber FavRankSubscriber qui permet de gérer le rang des favoris
class FavRankSubscriber implements EventSubscriberInterface
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

    // Définition de la méthode postPersist qui permet de gérer le rang des favoris après la modification du rang d'un favori
    public function postPersist(ViewEvent $event): void
    {
        // Récupération du favori modifié
        $fav = $event->getControllerResult();
        // Récupération de la méthode utilisée pour modifier le favori
        $method = $event->getRequest()->getMethod();

        // Si le favori n'est pas une instance de Favori ou que la méthode utilisée n'est pas PUT, on sort de la méthode
        if (!$fav instanceof Favori || $method !== Request::METHOD_PUT) {
            return;
        }

        // Récupération de l'utilisateur connecté
        $user = $this->tokenStorage->getToken()->getUser();
        // Vérification que l'utilisateur est bien une instance de User
        assert($user instanceof User);

        $favoriList = $user->getFavoris();

        // decalage des rangs des favoris des autres favoris qui ont un rang supérieur ou égal au favori modifié
        foreach ($favoriList as $item) {
            if ($item->getRank() >= $fav->getRank() && $item !== $fav) {
                $item->setRank($item->getRank() + 1);
            }
        }

        // Tri de la liste des favoris par rang
        $sortedList = $this->sortByRank($favoriList->toArray());

        // Correction des rangs des favoris
        $rank = 0;
        foreach ($sortedList as $data) {
            $data->setRank($rank);
            ++$rank;
        }

        // Enregistrement des modifications en base de données
        $this->entityManager->flush();
    }

    // Définition de la méthode sortByRank qui permet de trier un tableau d'objets Favori par rang
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
