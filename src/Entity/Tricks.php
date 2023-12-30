<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\TricksGroup;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TricksRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: TricksRepository::class)]
#[Vich\Uploadable]
class Tricks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $featuredImageName = null;

    #[Vich\UploadableField(mapping: 'featuredImage', fileNameProperty: 'featuredImageName')]
    private ?File $featuredImageFile = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TricksGroup $tricksGroup = null;

    #[ORM\OneToMany(mappedBy: 'tricks', targetEntity: TricksVideo::class, orphanRemoval: true, cascade:["persist"])]
    private Collection $tricksVideos;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TricksImage::class, orphanRemoval: true, cascade:["persist"])]
    private Collection $tricksImages;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->tricksVideos = new ArrayCollection();
        $this->tricksImages = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     */
    public function setFeaturedImageFile(?File $featuredImageFile = null): void
    {
        $this->featuredImageFile = $featuredImageFile;

        if (null !== $featuredImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFeaturedImageFile(): ?File
    {
        return $this->featuredImageFile;
    }

    public function getFeaturedImageName(): ?string
    {
        return $this->featuredImageName;
    }

    public function setFeaturedImageName(?string $featuredImageName): static
    {
        $this->featuredImageName = $featuredImageName;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
}
