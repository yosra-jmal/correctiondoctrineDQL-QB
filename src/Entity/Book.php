<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $ref = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $publicationDate = null;

    #[ORM\Column]
    private ?bool $enabled = null;
    #[ORM\Column(length: 255)]
    private ?string $category = null;



    #[ORM\ManyToMany(targetEntity: Reader::class, mappedBy: 'books')]

    private Collection $readers;


    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: "books")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author;

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(?string $category)
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
    public function __construct()
    {
        $this->readers = new ArrayCollection();
    }

    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function setRef($ref)
    {
        return $this->ref = $ref;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection<int, Reader>
     */
    public function getReaders(): Collection
    {
        return $this->readers;
    }

    public function addReader(Reader $reader): static
    {
        if (!$this->readers->contains($reader)) {
            $this->readers->add($reader);
            $reader->addBook($this);
        }

        return $this;
    }

    public function removeReader(Reader $reader): static
    {
        if ($this->readers->removeElement($reader)) {
            $reader->removeBook($this);
        }

        return $this;
    }
}
