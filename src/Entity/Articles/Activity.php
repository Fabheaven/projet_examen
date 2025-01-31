<?php

namespace App\Entity\Articles;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User;

#[ORM\Entity]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)] // Ajout du champ description
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)] // Ajout du champ price
    private ?float $price = null;

    #[ORM\ManyToMany(targetEntity: Circuit::class, mappedBy: 'activities')]
    private Collection $circuits;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;
    
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'string')]
    private string $state;

    #[ORM\OneToOne(inversedBy: 'activity', targetEntity: MediaFile::class, cascade: ['persist', 'remove'])]
    private ?MediaFile $mediafile;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'activities')]
    private Collection $users; // Relation inverse N,N avec User

    // Constantes pour les états de l'activité
    public const STATES = ['active', 'inactive', 'pending']; 

    public function __construct()
    {
        $this->circuits = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->state = self::STATES[0];  // Définit un état par défaut
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();  
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getCircuits(): Collection
    {
        return $this->circuits;
    }

    public function addCircuit(Circuit $circuit): self
    {
        if (!$this->circuits->contains($circuit)) {
            $this->circuits->add($circuit);
            $circuit->addActivity($this); // Met à jour l'autre côté de la relation
        }

        return $this;
    }

    public function removeCircuit(Circuit $circuit): self
    {
        if ($this->circuits->removeElement($circuit)) {
            $circuit->removeActivity($this); // Met à jour l'autre côté de la relation
        }

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): self
    {
        $this->users = $users;
        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedAt = new \DateTimeImmutable(); // Correction : majuscule remplacée par minuscule
    }

   
    public function getState()
    {
        return $this->state;
    }

    
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    
    public function getMediafile()
    {
        return $this->mediafile;
    }

   
    public function setMediafile($mediafile)
    {
        $this->mediafile = $mediafile;

        return $this;
    }
}
