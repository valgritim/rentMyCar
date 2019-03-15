<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Advert
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
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $seats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="advert", orphanRemoval=true)
     */
    private $advImages;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->advImages = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le slug!
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     */

    public function initializeSlug(){

        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): self
    {
        $this->seats = $seats;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getAdvImages(): Collection
    {
        return $this->advImages;
    }

    public function addAdvImage(Image $advImage): self
    {
        if (!$this->advImages->contains($advImage)) {
            $this->advImages[] = $advImage;
            $advImage->setAdvert($this);
        }

        return $this;
    }

    public function removeAdvImage(Image $advImage): self
    {
        if ($this->advImages->contains($advImage)) {
            $this->advImages->removeElement($advImage);
            // set the owning side to null (unless already changed)
            if ($advImage->getAdvert() === $this) {
                $advImage->setAdvert(null);
            }
        }

        return $this;
    }


}
