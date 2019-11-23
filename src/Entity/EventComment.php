<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventCommentRepository")
 */
class EventComment
{
    const STATUS_PENDING_MODERATION = 'pending_moderation';
    const STATUS_ONLINE = 'online';
    const STATUS_REFUSED = 'refused';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="comments")
     */
    private $event;

    /**
     * @ORM\Embedded(class="App\Entity\SfConnectUser", columnPrefix="author_")
     */
    private $author;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $postedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $contentRating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $foodRating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $locationRating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $socialEventRating;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $selectedTags = [];

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    public function __construct()
    {
        $this->author = new SfConnectUser();
        $this->postedAt = new \DateTimeImmutable();
        $this->status = self::STATUS_PENDING_MODERATION;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): SfConnectUser
    {
        return $this->author;
    }

    public function getPostedAt(): ?\DateTimeImmutable
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTimeImmutable $postedAt): self
    {
        $this->postedAt = $postedAt;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getContentRating(): ?int
    {
        return $this->contentRating;
    }

    public function setContentRating(?int $contentRating): self
    {
        $this->contentRating = $contentRating;

        return $this;
    }

    public function getFoodRating(): ?int
    {
        return $this->foodRating;
    }

    public function setFoodRating(?int $foodRating): self
    {
        $this->foodRating = $foodRating;

        return $this;
    }

    public function getLocationRating(): ?int
    {
        return $this->locationRating;
    }

    public function setLocationRating(?int $locationRating): self
    {
        $this->locationRating = $locationRating;

        return $this;
    }

    public function getSocialEventRating(): ?int
    {
        return $this->socialEventRating;
    }

    public function setSocialEventRating(?int $socialEventRating): self
    {
        $this->socialEventRating = $socialEventRating;

        return $this;
    }

    public function getSelectedTags(): ?array
    {
        return $this->selectedTags;
    }

    public function setSelectedTags(?array $selectedTags): self
    {
        $this->selectedTags = $selectedTags;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
