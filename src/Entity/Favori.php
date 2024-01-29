<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\FavoriRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

// Définition de l'entité Favori avec ses methodes Post, Put, Delete, Get, GetCollection (pour la collection de favoris)
// et la définition de la relation avec les entités User et Movie
// Les annotations ApiResource permettent de définir les opérations possibles sur l'entité
#[ORM\Entity(repositoryClass: FavoriRepository::class)]
#[ApiResource(
    operations: [
        // Définition des opérations possibles sur l'entité Favori avec les restrictions de sécurité
        new GetCollection(
            security: 'is_granted("ROLE_USER")',
        ),
        new Post(
            security: 'is_granted("ROLE_USER")',
        ),
        new Put(
            security: 'is_granted("ROLE_USER")',
        ),
        new Delete(
            security: 'is_granted("ROLE_USER")',
        ),
        new Get(
            security: 'is_granted("ROLE_USER")',
        ),
    ],
    normalizationContext: ['groups' => ['favori:read']],
)]
class Favori
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'favori:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['user:read', 'favori:read'])]
    private ?int $rank = null;

    #[ORM\ManyToOne(inversedBy: 'favoris')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['favori:read'])]
    private ?User $owner = null; // Relation avec l'entité User

    #[ORM\ManyToOne(inversedBy: 'favoris')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:read'])]
    private ?Movie $movie = null; // Relation avec l'entité Movie

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }
}
