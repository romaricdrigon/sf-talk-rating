<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
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

    /**
     * @var Collection|null cache so Doctrine do not re-send queries to DB
     */
    private $cachedOnlineReviews;

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

    /**
     * @return Collection|TalkReview[]
     */
    public function getOnlineReviews(): Collection
    {
        if ($this->cachedOnlineReviews) {
            return $this->cachedOnlineReviews;
        }

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('status', TalkReview::STATUS_ONLINE));

        return $this->cachedOnlineReviews = $this->reviews->matching($criteria);
    }

    /**
     * Can given User review event?
     * Event should have occurred less than REVIEW_PERIOD ago,
     * and user should not have already reviewed talk,
     * even if his review was not published or declined.
     *
     * @param string $userUuid SymfonyConnect User UUID
     * @return bool
     */
    public function canBeReviewed(string $userUuid): bool
    {
        if (new \DateTimeImmutable('now') > $this->event->getReviewDeadline()) {
            return false;
        }

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('author.uuid', $userUuid));

        $myReviews = $this->reviews->matching($criteria);

        return 0 === count($myReviews);
    }

    public function getAverageRating(): ?int
    {
        $onlineReviews = $this->getOnlineReviews();

        if (!count($onlineReviews)) {
            return null;
        }

        $grades = $onlineReviews->map(function (TalkReview $review) {
            return $review->getRating();
        })->toArray();

        return array_sum($grades) / count($grades);
    }
}
