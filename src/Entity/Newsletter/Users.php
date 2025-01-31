<?php

namespace App\Entity\Newsletter;

use App\Repository\Newsletter\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Email(message: "L'adresse email n'est pas valide.")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.(fr|com)$/",
        message: "L'adresse email doit être de la forme nom@nom.fr ou nom@nom.com"
    )]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isRgpd = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $validationToken = null;

    #[ORM\Column]
    private ?bool $isValid = false;

    /**
     * @var Collection<int, Categories>
     */
    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy:'users')]
    private Collection $categories;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // Getter et Setter corrigés pour la propriété isRgpd
    public function getIsRgpd(): ?bool
    {
        return $this->isRgpd;
    }

    public function setIsRgpd(bool $isRgpd): self
    {
        $this->isRgpd = $isRgpd;
        return $this;
    }

    public function getValidationToken(): ?string
    {
        return $this->validationToken;
    }

    public function setValidationToken(?string $validationToken): self
    {
        $this->validationToken = $validationToken;
        return $this;
    }


    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setValid(bool $isValid): self
    {
        $this->isValid = $isValid;
        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            //$category->addUser($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        $this->categories->removeElement($category);
        
        return $this;
    }
}
