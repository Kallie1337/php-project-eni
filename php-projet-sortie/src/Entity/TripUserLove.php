<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TripUserLoveRepository")
 */
class TripUserLove
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trip", inversedBy="tripUserLove")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trip;



    public function getId(): ?int
    {
        return $this->id;
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

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): self
    {
        $this->trip = $trip;

        return $this;
    }



}
