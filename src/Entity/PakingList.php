<?php

namespace App\Entity;

use App\Repository\PakingListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PakingListRepository::class)]
class PakingList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isPredefined = null;

    #[ORM\ManyToMany(targetEntity: Trip::class, mappedBy: 'fk_paking_list')]
    private Collection $fk_trips;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $season_filter = null;

    public function __construct()
    {
        $this->fk_trips = new ArrayCollection();
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

    public function isIsPredefined(): ?bool
    {
        return $this->isPredefined;
    }

    public function setIsPredefined(bool $isPredefined): static
    {
        $this->isPredefined = $isPredefined;

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
            $fkTrip->addFkPakingList($this);
        }

        return $this;
    }

    public function removeFkTrip(Trip $fkTrip): static
    {
        if ($this->fk_trips->removeElement($fkTrip)) {
            $fkTrip->removeFkPakingList($this);
        }

        return $this;
    }

    public function getSeasonFilter(): ?string
    {
        return $this->season_filter;
    }

    public function setSeasonFilter(?string $season_filter): static
    {
        $this->season_filter = $season_filter;

        return $this;
    }
}
