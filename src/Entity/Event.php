<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;


/**
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
     * @Assert\Length(min = 3)
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text", length=255)
     */
    private $description;

    /**
     * @Assert\NotBlank()
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @Assert\NotBlank() 
     * @Assert\DateTime()
     * @Assert\GreaterThan("today")
     * @ORM\Column(type="datetime")
     */
    private $start_at;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     */
    private $end_at;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @Assert\NotBlank()
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
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\Place")
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    /**
     * @Assert\NotBlank(message="Please, upload the poster as a jpg or png file.")
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png" })
     * @ORM\Column(type="string", length=255)
     */
    private $poster;

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

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->start_at;
    }

    public function setStartAt(\DateTimeInterface $start_at): self
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeInterface $end_at): self
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


}
