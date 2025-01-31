<?php

namespace App\Entity\Articles;

use App\Repository\Articles\MediaFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File;

#[ORM\Entity(repositoryClass: MediaFileRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]

class MediaFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'articles_mediafile', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string')]
    private ?string $imageName = null;
    
    #[ORM\Column(type: 'integer')]
    private ?int $imageSize = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;
    
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    // Relation OneToOne avec Circuit
    #[ORM\OneToOne(mappedBy: 'mediafile', targetEntity: Circuit::class, cascade: ['persist', 'remove'])]
    private ?Circuit $circuit = null;

    // Relation OneToOne avec Activity
    #[ORM\OneToOne(mappedBy: 'mediafile', targetEntity: Activity::class, cascade: ['persist', 'remove'])]
    private ?Activity $activity = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();  
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedAt = new \DateTimeImmutable(); // Mise à jour de la date
    }

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable(); // Mettre à jour la date lors du changement de fichier
        }
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;
        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(int $imageSize): self
    {
        $this->imageSize = $imageSize;
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

     
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    
    public function setCreatedAt($createdAt):self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

