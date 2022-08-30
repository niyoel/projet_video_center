<?php

namespace App\Entity;
use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\Timestampable;
#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ORM\Table(name:"videos")]
#[ORM\HasLifecycleCallbacks]
class Video
{ 
    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank(message:"Votre champs ne peut pas être vide")]
    #[Assert\Length(min:3, minMessage:'Vous devez avoir un titre de minimum {{ limit }} caractères ! ')]
    private $title;

    #[ORM\Column(type: 'string', length: 500)]
    #[Assert\NotBlank(message:"Votre champs ne peut pas être vide")]
    private $videoLink;
    
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message:"Votre champs ne peut pas être vide")]
    #[Assert\Length(min:3, minMessage:'Vous devez avoir une description de minimum {{ limit }} caractères ! ')]
    private $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'videos')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'boolean')]
    private $isPremium;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(string $videoLink): self
    {
        $this->videoLink = $videoLink;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isIsPremium(): ?bool
    {
        return $this->isPremium;
    }

    public function setIsPremium(bool $isPremium): self
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    
}
