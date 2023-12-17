<?php

namespace App\Entity;

use App\Repository\TripPackingListItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripPackingListItemRepository::class)]
class TripPackingListItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trip::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip;

    #[ORM\ManyToOne(targetEntity: PakingList::class, inversedBy: "tripPackingListItems")]
    #[ORM\JoinColumn(nullable: false)]
    private ?PakingList $pakingList;

    #[ORM\Column]
    private ?int $count = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(Trip $trip): static
    {
        $this->trip = $trip;

        return $this;
    }

    public function getPakingList(): ?PakingList
    {
        return $this->pakingList;
    }

    public function setPakingList(PakingList $pakingList): static
    {
        $this->pakingList = $pakingList;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }
}
