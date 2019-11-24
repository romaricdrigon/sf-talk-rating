<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    const STATUS_DRAFT = 'draft';
    const STATUS_ONLINE = 'online';

    private const REVIEW_PERIOD = '30 days';

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
     * @ORM\Column(type="date_immutable")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $badgeUrl;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Talk", mappedBy="event")
     */
    private $talks;

    /**
     * @ORM\OneToMany(targetEntity="EventReview", mappedBy="event")
     */
    private $reviews;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @var Collection|null cache so Doctrine do not re-send queries to DB
     */
    private $cachedOnlineReviews;

    public function __construct()
    {
        $this->talks = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->status = self::STATUS_DRAFT;
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

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getBadgeUrl(): ?string
    {
        return $this->badgeUrl;
    }

    public function setBadgeUrl(?string $badgeUrl): self
    {
        $this->badgeUrl = $badgeUrl;

        return $this;
    }

    /**
     * @return Collection|Talk[]
     */
    public function getTalks(): Collection
    {
        return $this->talks;
    }

    public function addTalk(Talk $talk): self
    {
        if (!$this->talks->contains($talk)) {
            $this->talks[] = $talk;
            $talk->setEvent($this);
        }

        return $this;
    }

    public function removeTalk(Talk $talk): self
    {
        if ($this->talks->contains($talk)) {
            $this->talks->removeElement($talk);
            // set the owning side to null (unless already changed)
            if ($talk->getEvent() === $this) {
                $talk->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventReview[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(EventReview $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setEvent($this);
        }

        return $this;
    }

    public function removeReview(EventReview $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getEvent() === $this) {
                $review->setEvent(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isOnline(): bool
    {
        return self::STATUS_ONLINE === $this->status;
    }

    /**
     * @return Collection|EventReview[]
     */
    public function getOnlineReviews(): Collection
    {
        if ($this->cachedOnlineReviews) {
            return $this->cachedOnlineReviews;
        }

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('status', EventReview::STATUS_ONLINE));

        return $this->cachedOnlineReviews = $this->reviews->matching($criteria);
    }

    /**
     * Can given User review event?
     * Event should have occurred less than REVIEW_PERIOD ago,
     * and user should not have already reviewed event,
     * even if his review was not published or declined.
     *
     * @param string $userUuid SymfonyConnect User UUID
     * @return bool
     */
    public function canBeReviewed(string $userUuid): bool
    {
        if (new \DateTimeImmutable('now') > $this->getReviewDeadline()) {
            return false;
        }

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('author.uuid', $userUuid));

        $myReviews = $this->reviews->matching($criteria);

        return 0 === count($myReviews);
    }

    public function getReviewDeadline(): \DateTimeImmutable
    {
        return $this->endDate->modify('+'.self::REVIEW_PERIOD);
    }

    public function getAverageRating(): ?int
    {
        $onlineReviews = $this->getOnlineReviews();

        if (!count($onlineReviews)) {
            return null;
        }

        $grades = $onlineReviews->map(function (EventReview $review) {
            return $review->getRating();
        })->toArray();

        return array_sum($grades) / count($grades);
    }
}
