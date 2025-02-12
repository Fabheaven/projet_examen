<?php

namespace App\Entity\Articles;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks] // Active les événements Doctrine
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: "Le slug ne peut pas être vide.")]
    private ?string $slug = null; // Rend le slug nullable pour éviter l'erreur

    #[ORM\ManyToMany(targetEntity: Activity::class, mappedBy: 'categories')]
    private Collection $activities;

    #[ORM\ManyToMany(targetEntity: Circuit::class, mappedBy: 'categories')]
    private Collection $circuits;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->circuits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateSlug(): void
    {
        if (empty($this->slug) && !empty($this->name)) {
            $slugger = new \Symfony\Component\String\Slugger\AsciiSlugger();
            $this->slug = $slugger->slug($this->name)->lower();
        }
    }

    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->addCategory($this);
        }
        return $this;
    }
    
    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            $activity->removeCategory($this);
        }
        return $this;
    }

    public function getCircuits(): Collection
    {
        return $this->circuits;
    }

    public function addCircuit(Circuit $circuit): self
    {
        if (!$this->circuits->contains($circuit)) {
            $this->circuits[] = $circuit;
            $circuit->addCategory($this);
        }
        return $this;
    }

    public function removeCircuit(Circuit $circuit): self
    {
        if ($this->circuits->removeElement($circuit)) {
            $circuit->removeCategory($this);
        }
        return $this;
    }
}
