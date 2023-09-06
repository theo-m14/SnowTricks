<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TricksRepository::class)]
class Tricks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TricksGroup $tricksGroup = null;

    #[ORM\OneToMany(mappedBy: 'tricks', targetEntity: TricksVideo::class, orphanRemoval: true)]
    private Collection $tricksVideos;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TricksImage::class, orphanRemoval: true)]
    private Collection $tricksImages;

    public function __construct()
    {
        $this->tricksVideos = new ArrayCollection();
        $this->tricksImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTricksGroup(): ?TricksGroup
    {
        return $this->tricksGroup;
    }

    public function setTricksGroup(?TricksGroup $tricksGroup): static
    {
        $this->tricksGroup = $tricksGroup;

        return $this;
    }

    /**
     * @return Collection<int, TricksVideo>
     */
    public function getTricksVideos(): Collection
    {
        return $this->tricksVideos;
    }

    public function addTricksVideo(TricksVideo $tricksVideo): static
    {
        if (!$this->tricksVideos->contains($tricksVideo)) {
            $this->tricksVideos->add($tricksVideo);
            $tricksVideo->setTricks($this);
        }

        return $this;
    }

    public function removeTricksVideo(TricksVideo $tricksVideo): static
    {
        if ($this->tricksVideos->removeElement($tricksVideo)) {
            // set the owning side to null (unless already changed)
            if ($tricksVideo->getTricks() === $this) {
                $tricksVideo->setTricks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TricksImage>
     */
    public function getTricksImages(): Collection
    {
        return $this->tricksImages;
    }

    public function addTricksImage(TricksImage $tricksImage): static
    {
        if (!$this->tricksImages->contains($tricksImage)) {
            $this->tricksImages->add($tricksImage);
            $tricksImage->setTrick($this);
        }

        return $this;
    }

    public function removeTricksImage(TricksImage $tricksImage): static
    {
        if ($this->tricksImages->removeElement($tricksImage)) {
            // set the owning side to null (unless already changed)
            if ($tricksImage->getTrick() === $this) {
                $tricksImage->setTrick(null);
            }
        }

        return $this;
    }
}
