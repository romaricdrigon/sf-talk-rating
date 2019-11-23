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
     * @ORM\Column(type="json", nullable=true)
     */
    private $tags = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TalkComment", mappedBy="talk", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean")
     */
    private $firstTimeSpeaker;

    public function __construct()
    {
        $this->speaker = new SfConnectUser();
        $this->comments = new ArrayCollection();
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

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return Collection|TalkComment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(TalkComment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTalk($this);
        }

        return $this;
    }

    public function removeComment(TalkComment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTalk() === $this) {
                $comment->setTalk(null);
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
