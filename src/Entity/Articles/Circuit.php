<?php

namespace App\Entity\Articles;

use App\Repository\Articles\CircuitRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Articles\Category;
use App\Entity\User;
use App\Entity\Articles\MediaFile;

#[ORM\Entity(repositoryClass: CircuitRepository::class)]
#[ORM\HasLifecycleCallbacks] // Ajouté pour activer les callbacks de cycle de vie
class Circuit
{
    public const AVAILABLES = ['Disponible', 'Indisponible'];
    public const STATES = ['Actif', 'Inactif'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank()]
    private string $slug = '';

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $price = null;

    #[ORM\Column(type: 'integer')]
    private ?int $duration = null; // Durée en heures ou jours

    #[ORM\Column(type: 'boolean')]
    private ?bool $availability = null; // Disponibilité

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;
    
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'string')]
    private string $state;

    #[ORM\OneToOne(inversedBy: 'circuit', targetEntity: MediaFile::class, cascade: ['persist', 'remove'])]
    private ?MediaFile $mediafile = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'circuits')]
    private Collection $users; // Relation inverse N,N avec User

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'circuits')]
    #[ORM\JoinTable(name: "circuit_category")]
    private Collection $categories;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();  // Initialiser la date de création
        $this->updatedAt = new \DateTimeImmutable();  // Initialiser la date de mise à jour
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

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;
        return $this;
    }


    public function isAvailable(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): self
    {
        $this->availability = $availability;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }
        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
        return $this;
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedAt = new \DateTimeImmutable(); // Mise à jour correcte de updatedAt
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

  
    public function getSlug()
    {
        return $this->slug;
    }

   
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

   
    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)){
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }
}
