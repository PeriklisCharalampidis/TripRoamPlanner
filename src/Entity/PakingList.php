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



    #[ORM\Column(length: 50, nullable: true)]
    private ?string $season_filter = null;




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
