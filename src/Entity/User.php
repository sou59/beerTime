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
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=30)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\NotBlank()
     */
    // ajouter une regex pour imposer un format lettre a ou b et chiffre
    private $zip;

    /**
     * @ORM\Column(type="date")
     * @Assert\Range(
     *      min = "-120 years",
     *      max = "-18 years"
     * )
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Country()
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

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $roles;

    public function __construct()
    {
        $this->eventUser = new ArrayCollection();
        $this->registUser = new ArrayCollection();
        // ligne non obligatoire
       // $this->roles = array('ROLE_USER');
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

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
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

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

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

    


    public function getRoles()
    {
        //return $this->roles;
        return array('ROLE_USER');
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    
}
