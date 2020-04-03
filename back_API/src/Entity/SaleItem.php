<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleItemRepository")
 */
class SaleItem
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer")
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
     * @ORM\Column(type="json")
     */
    private $price = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user_from;

    /**
     * @ORM\Column(type="json")
     */
    private $image = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_dark;

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

    public function getPrice(): ?array
    {
        return $this->price;
    }

    public function setPrice(array $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUserFrom(): ?string
    {
        return $this->user_from;
    }

    public function setUserFrom(string $user_from): self
    {
        $this->user_from = $user_from;

        return $this;
    }

    public function getImage(): ?array
    {
        return $this->image;
    }

    public function setImage(array $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIsDark(): ?bool
    {
        return $this->is_dark;
    }

    public function setIsDark(bool $is_dark): self
    {
        $this->is_dark = $is_dark;

        return $this;
    }
}
