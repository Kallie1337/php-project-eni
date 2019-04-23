<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TripRepository")
 */
class Trip
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $beginDateTime;



    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationEndDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxRegistration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $infos;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentRegistration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDateTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $weather;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $timezone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $administration;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TripUserLove", mappedBy="trip", cascade={"persist"})
     */
    private $love;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TripUserLove", mappedBy="trip", orphanRemoval=true)
     */
    private $tripUserLove;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hang;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="trips")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Historic", mappedBy="trip", cascade={"persist", "remove"})
     */
    private $historic;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived;

    public function __construct()
    {
        $this->love = new ArrayCollection();
        $this->tripUserLove = new ArrayCollection();
        $this->users = new ArrayCollection();
    }



    public function getId(): ?int
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

    public function getBeginDateTime(): ?\DateTimeInterface
    {
        return $this->beginDateTime;
    }

    public function setBeginDateTime(\DateTimeInterface $beginDateTime): self
    {
        $this->beginDateTime = $beginDateTime;

        return $this;
    }


    public function getRegistrationEndDate(): ?\DateTimeInterface
    {
        return $this->registrationEndDate;
    }

    public function setRegistrationEndDate(\DateTimeInterface $registrationEndDate): self
    {
        $this->registrationEndDate = $registrationEndDate;

        return $this;
    }

    public function getMaxRegistration(): ?int
    {
        return $this->maxRegistration;
    }

    public function setMaxRegistration(int $maxRegistration): self
    {
        $this->maxRegistration = $maxRegistration;

        return $this;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }

    public function getCurrentRegistration(): ?int
    {
        return $this->currentRegistration;
    }

    public function setCurrentRegistration(int $currentRegistration): self
    {
        $this->currentRegistration = $currentRegistration;

        return $this;
    }

    public function getEndDateTime(): ?\DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(\DateTimeInterface $endDateTime): self
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(string $weather): self
    {
        $this->weather = $weather;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getAdministration(): ?string
    {
        return $this->administration;
    }

    public function setAdministration(string $administration): self
    {
        $this->administration = $administration;

        return $this;
    }

    /**
     * @return Collection|TripUserLove[]
     */
    public function getLove(): Collection
    {
        return $this->love;
    }

    public function addLove(TripUserLove $love): self
    {
        if (!$this->love->contains($love)) {
            $this->love[] = $love;
            $love->setTrip($this);
        }
        return $this;
    }

    public function removeLove(TripUserLove $love): self
    {
        if ($this->love->contains($love)) {
            $this->love->removeElement($love);
            // set the owning side to null (unless already changed)
            if ($love->getTrip() === $this) {
                $love->setTrip(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TripUserLove[]
     */
    public function getTripUserLove(): Collection
    {
        return $this->tripUserLove;
    }

    public function addTripUserLove(TripUserLove $tripUserLove): self
    {
        if (!$this->tripUserLove->contains($tripUserLove)) {
            $this->tripUserLove[] = $tripUserLove;
            $tripUserLove->setTrip($this);
        }

        return $this;
    }

    public function removeTripUserLove(TripUserLove $tripUserLove): self
    {
        if ($this->tripUserLove->contains($tripUserLove)) {
            $this->tripUserLove->removeElement($tripUserLove);
            // set the owning side to null (unless already changed)
            if ($tripUserLove->getTrip() === $this) {
                $tripUserLove->setTrip(null);
            }
        }

        return $this;
    }

    public function getHang(): ?string
    {
        return $this->hang;
    }

    public function setHang(string $hang): self
    {
        $this->hang = $hang;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
               }

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getHistoric(): ?Historic
    {
        return $this->historic;
    }

    public function setHistoric(?Historic $historic): self
    {
        $this->historic = $historic;

        // set (or unset) the owning side of the relation if necessary
        $newTrip = $historic === null ? null : $this;
        if ($newTrip !== $historic->getTrip()) {
            $historic->setTrip($newTrip);
        }

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }



}
