<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Service\FileUploader;
use App\Repository\EventRepository;

/**
 * @UniqueEntity("name")
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Please, insert a name for this event.")
     * @Assert\Length(
     *  min = 3,
     *  minMessage = "Le nom de l'évènement doit comporter au moins {{ limit }} caractères"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Please, insert a name for this event.")
     * @Assert\Length(
     *  min = 10,
     *  max = 500,
     *  minMessage = "La description de l'évènement doit comporter au moins {{ limit }} caractères",
     *  maxMessage = "La description de l'évènement ne doit pas excéder {{ limit }} caractères"
     * )
     * @ORM\Column(type="text", length=255)
     */
    private $description;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0, message = "Nombre de participants maximun invalide")
     * @Assert\Type(
     *  type="integer",
     *  message = "La value {{ value }} n'est pas valid"
     * )
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @Assert\NotBlank() 
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $start_at;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @Assert\Expression(
     *   "this.getStartAt() < this.getEndAt()",
     *    message="La date de fin ne peut pas se terminer avant le début"
     * )
     * @ORM\Column(type="datetime")
     */
    private $end_at;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0, message="Montant invalide")
     * @Assert\Type(
     *  type="integer",
     *  message = "La value {{ value }} n'est pas valid"
     * )
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="events")
     */
    private $category;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="eventUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="registUser")
     */
    private $registration;

    /**
     * @Assert\NotBlank(message = "Merci de rentrer une adresse valide")
     * @ORM\ManyToOne(targetEntity="App\Entity\Place")
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $poster;

    /**
     * @Assert\Image(
     *     maxSize = "2M",
     *     maxSizeMessage = "Votre fichier ne doit pas dépasser {{  limit }}"
     * )
     */
    private $posterFile;

    /**
     * @Assert\Url()
     */
    private $posterUrl;


    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->registration = new ArrayCollection();
    }

    public function getId()
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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getStartAt()
    {
        return $this->start_at;
    }

    public function setStartAt($start_at)
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt()
    {
        return $this->end_at;
    }

    public function setEndAt($end_at)
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }


    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRegistration(): Collection
    {
        return $this->registration;
    }

    public function addRegistration(User $registration): self
    {
        if (!$this->registration->contains($registration)) {
            $this->registration[] = $registration;
        }

        return $this;
    }

    public function removeRegistration(User $registration): self
    {
        if ($this->registration->contains($registration)) {
            $this->registration->removeElement($registration);
        }

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getPoster()
    {
        return $this->poster;
    }

    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    public function getPosterFile()
    {
        return $this->posterFile;
    }

    public function setPosterFile( $posterFile)
    {
        $this->posterFile = $posterFile;

        return $this;
    }

    public function getPosterUrl()
    {
        return $this->posterUrl;
    }

    public function setPosterUrl( $posterUrl)
    {
        $this->posterUrl = $posterUrl;

        return $this;
    }


    // callback sans paranthese pas de paramètres
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // vérification si posterUrl ou posterFile est renseigné
        if( null === $this->posterFile && empty($this->posterURL)) {
            $context->buildViolation('Veuillez envoyer une image ou indiquer l\'URL d\'une image')
                ->atPath('posterFile')
                ->addViolation();

            $context->buildViolation('Veuillez envoyer l\'URL d\'une image ou importer une image')
            ->atPath('posterURL')
            ->addViolation();    
        }
    }
}
