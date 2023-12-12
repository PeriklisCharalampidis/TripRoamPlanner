<?php

namespace App\Entity;

use App\Repository\ActivitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivitiesRepository::class)]
class Activities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 100)]
    private ?string $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?bool $isPredefined = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Trip::class, mappedBy: 'fk_activities')]
    private Collection $fk_trips;

    #[ORM\Column(length: 100)]
    private ?string $destination_filter = null;

    public function __construct()
    {
        $this->fk_trips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function isIsPredefined(): ?bool
    {
        return $this->isPredefined;
    }

    public function setIsPredefined(bool $isPredefined): static
    {
        $this->isPredefined = $isPredefined;

        return $this;
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

    /**
     * @return Collection<int, Trip>
     */
    public function getFkTrips(): Collection
    {
        return $this->fk_trips;
    }

    public function addFkTrip(Trip $fkTrip): static
    {
        if (!$this->fk_trips->contains($fkTrip)) {
            $this->fk_trips->add($fkTrip);
            $fkTrip->addFkActivity($this);
        }

        return $this;
    }

    public function removeFkTrip(Trip $fkTrip): static
    {
        if ($this->fk_trips->removeElement($fkTrip)) {
            $fkTrip->removeFkActivity($this);
        }

        return $this;
    }

    public function getDestinationFilter(): ?string
    {
        return $this->destination_filter;
    }

    public function setDestinationFilter(string $destination_filter): static
    {
        $this->destination_filter = $destination_filter;

        return $this;
    }
}
