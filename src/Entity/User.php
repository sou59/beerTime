<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Entity\Event;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterFace 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $zip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="owner", orphanRemoval=true)
     */
    private $eventUser;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="registration")
     */
    private $registUser;

    public function __construct()
    {
        $this->eventUser = new ArrayCollection();
        $this->registUser = new ArrayCollection();
    }

    public function eraseCredentials()
    {}

    public function getSalt(): ?string
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEventUser(): Collection
    {
        return $this->eventUser;
    }

    public function addEventUser(Event $eventUser): self
    {
        if (!$this->eventUser->contains($eventUser)) {
            $this->eventUser[] = $eventUser;
            $eventUser->setOwner($this);
        }

        return $this;
    }

    public function removeEventUser(Event $eventUser): self
    {
        if ($this->eventUser->contains($eventUser)) {
            $this->eventUser->removeElement($eventUser);
            // set the owning side to null (unless already changed)
            if ($eventUser->getOwner() === $this) {
                $eventUser->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getRegistUser(): Collection
    {
        return $this->registUser;
    }

    public function addRegistUser(Event $registUser): self
    {
        if (!$this->registUser->contains($registUser)) {
            $this->registUser[] = $registUser;
            $registUser->addRegistration($this);
        }

        return $this;
    }

    public function removeRegistUser(Event $registUser): self
    {
        if ($this->registUser->contains($registUser)) {
            $this->registUser->removeElement($registUser);
            $registUser->removeRegistration($this);
        }

        return $this;
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
    
}
