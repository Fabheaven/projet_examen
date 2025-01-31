<?php

namespace App\Entity\Articles;
use App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Comment
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private ?int $id = null;

#[ORM\Column(type: 'text')]
private ?string $content = null;

#[ORM\Column(type: 'datetime')]
private \DateTime $createdAt;

#[ORM\ManyToOne(targetEntity: User::class)]
#[ORM\JoinColumn(nullable: false)]
private User $author;

#[ORM\ManyToOne(targetEntity: Circuit::class, inversedBy: 'comments')]
private ?Circuit $circuit = null;

#[ORM\ManyToOne(targetEntity: Activity::class, inversedBy: 'comments')]
private ?Activity $activity = null;

// Getters and setters
public function getId(): ?int
{
return $this->id;
}

public function getContent(): ?string
{
return $this->content;
}

public function setContent(string $content): self
{
$this->content = $content;
return $this;
}

public function getCreatedAt(): \DateTime
{
return $this->createdAt;
}

public function setCreatedAt(\DateTime $createdAt): self
{
$this->createdAt = $createdAt;
return $this;
}

public function getAuthor(): User
{
return $this->author;
}

public function setAuthor(User $author): self
{
$this->author = $author;
return $this;
}

public function getCircuit(): ?Circuit
{
return $this->circuit;
}

public function setCircuit(?Circuit $circuit): self
{
$this->circuit = $circuit;
return $this;
}

public function getActivity(): ?Activity
{
return $this->activity;
}

public function setActivity(?Activity $activity): self
{
$this->activity = $activity;
return $this;
}

}
