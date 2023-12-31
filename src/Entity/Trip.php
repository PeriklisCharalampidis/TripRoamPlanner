<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints\Type;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $destination = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_begin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\ManyToMany(targetEntity: Activities::class, inversedBy: 'fk_trips', cascade: ['persist'])]
    private Collection $fk_activities;


    #[ORM\OneToMany(mappedBy: 'fk_trip', targetEntity: JournalPost::class)]
    private Collection $fk_journal_post;

    #[ORM\ManyToOne(inversedBy: 'fk_trips')]
    private ?User $fk_user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->fk_activities = new ArrayCollection();
        $this->fk_journal_post = new ArrayCollection();
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

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDateBegin(): ?\DateTimeInterface
    {
        return $this->date_begin;
    }

    public function setDateBegin(\DateTimeInterface $date_begin): static
    {
        $this->date_begin = $date_begin;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): static
    {
        $this->date_end = $date_end;

        return $this;
    }

    /**
     * @return Collection<int, Activities>
     */
    public function getFkActivities(): Collection
    {
        return $this->fk_activities;
    }

    public function addFkActivity(Activities $fkActivity): static
    {
        if (!$this->fk_activities->contains($fkActivity)) {
            $this->fk_activities->add($fkActivity);
        }

        return $this;
    }

    public function removeFkActivity(Activities $fkActivity): static
    {
        $this->fk_activities->removeElement($fkActivity);

        return $this;
    }

    /**
     * @return Collection<int, JournalPost>
     */
    public function getFkJournalPost(): Collection
    {
        return $this->fk_journal_post;
    }

    public function addFkJournalPost(JournalPost $fkJournalPost): static
    {
        if (!$this->fk_journal_post->contains($fkJournalPost)) {
            $this->fk_journal_post->add($fkJournalPost);
            $fkJournalPost->setFkTrip($this);
        }

        return $this;
    }

    public function removeFkJournalPost(JournalPost $fkJournalPost): static
    {
        if ($this->fk_journal_post->removeElement($fkJournalPost)) {
            // set the owning side to null (unless already changed)
            if ($fkJournalPost->getFkTrip() === $this) {
                $fkJournalPost->setFkTrip(null);
            }
        }

        return $this;
    }

    public function getFkUser(): ?User
    {
        return $this->fk_user;
    }

    public function setFkUser(?User $fk_user): static
    {
        $this->fk_user = $fk_user;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
