<?php

namespace App\Entity\Articles;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Articles\Activity;
use App\Entity\Articles\Circuit;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'category')]
    private Collection $activities;

    #[ORM\OneToMany(targetEntity: Circuit::class, mappedBy: 'category')]
    private Collection $circuits;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->circuits = new ArrayCollection();
    }

    // Getters et Setters
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

    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setCategory($this);
        }
        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($activity->getCategory() === $this) {
            $activity->setCategory(null);
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
            $circuit->setCategory($this);
        }
        return $this;
    }

    public function removeCircuit(Circuit $circuit): self
    {
        if ($this->circuits->removeElement($circuit)) {
            if ($circuit->getCategory() === $this);
                $circuit->setCategory( null);
        }
        return $this;
    }
}
