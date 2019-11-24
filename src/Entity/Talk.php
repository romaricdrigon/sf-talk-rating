<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TalkRepository")
 */
class Talk
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="talks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\Embedded(class="App\Entity\SfConnectUser", columnPrefix="speaker_")
     */
    private $speaker;

    /**
     * @ORM\OneToMany(targetEntity="TalkReview", mappedBy="talk", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\Column(type="boolean")
     */
    private $firstTimeSpeaker;

    public function __construct()
    {
        $this->speaker = new SfConnectUser();
        $this->reviews = new ArrayCollection();
        $this->firstTimeSpeaker = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getSpeaker(): SfConnectUser
    {
        return $this->speaker;
    }

    /**
     * @return Collection|TalkReview[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addComment(TalkReview $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setTalk($this);
        }

        return $this;
    }

    public function removeReview(TalkReview $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getTalk() === $this) {
                $review->setTalk(null);
            }
        }

        return $this;
    }

    public function getFirstTimeSpeaker(): ?bool
    {
        return $this->firstTimeSpeaker;
    }

    public function setFirstTimeSpeaker(bool $firstTimeSpeaker): self
    {
        $this->firstTimeSpeaker = $firstTimeSpeaker;

        return $this;
    }
}
