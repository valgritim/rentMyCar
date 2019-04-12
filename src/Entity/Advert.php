<?php

namespace App\Entity;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert; //vérifications pour la validation des champs
/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"title"},
 *  message="Une autre annonce posède déjà ce titre, merci de modifier!")
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
     * @Assert\Length(min=10, max=255, minMessage="Le titre doit faire plus de 10 caractères", maxMessage="Le titre doit faire moins de 255 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     * @Assert\Length(min=2, minMessage="Le montant doit avoir minimum 2 chiffres")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20, minMessage="Le titre doit faire plus de 20 caractères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=100, minMessage="Votre annonce doit faire plus de 100 caractères")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $seats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="advert", orphanRemoval=true)
     * @Assert\Valid() 
     */

     //valid permet de forcer symfony à valider les sous formulaires dc les images
    private $images;

     /**
      * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="adverts")
      * @ORM\JoinColumn(nullable=false)
      */
     private $author;

     /**
      * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="advert")
      */
     private $bookings;

     /**
      * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="advert", orphanRemoval=true)
      */
     private $comments;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
        //$this->images = new ArrayCollection();
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
    /**
     * Permet de récupérer le commentaire d'un user pour une annonce
     * 
     * @param User $author
     * @return Comment|null
     */
    public function getCommentFromAuthor(User $user){

        foreach($this->comments as $comment){
            if($comment->getAuthor() === $user) return $comment;
        }
        return null;
    }
    /**
     * Permet d'obtenir la moyenne des notes pour cette annonce
     * 
     * @return float
     *
     * @return void
     */
    public function getAvgRatings(){
        //calculer la somme des notations
        $sum = array_reduce($this->comments->toArray(), function($total, $comment){
            return $total + $comment->getRating();
        },0);
        //calculer la moyenne
        if (count($this->comments) > 0) return $sum / count($this->comments); 
        return 0;
    }

    /**
     * Permet d'obtenir un tableau des jours non dispo pour le véhicule
     *
     * @return array //tableau d'objet DateTime représentant les jours d'occupation
     */
    public function getNotAvailableDays(){
        $notAvailableDays = [];
        foreach($this->bookings as $booking){
            //Calculer les jours qui se trouvent entre la date de depart et la date de restitution
            $resultat = range(
                $booking->getStartDate()->getTimestamp(), //va chercher date depart et transforme en timestamp
                $booking->getEndDate()->getTimestamp(), //idem date restitution
                24 * 60 * 60 //24h*60min*60s
            );
            $days = array_map(function($dayTimestamp){
                return new \DateTime(date('Y-m-d', $dayTimestamp));
            }, $resultat);
            //je remappe mon tableau en tableau ayant les dates au format date à partir du timestamp de chaque jour
            $notAvailableDays = array_merge($notAvailableDays, $days);
        }
        return $notAvailableDays;

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
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAdvert($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAdvert() === $this) {
                $image->setAdvert(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAdvert($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getAdvert() === $this) {
                $booking->setAdvert(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAdvert($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAdvert() === $this) {
                $comment->setAdvert(null);
            }
        }

        return $this;
    }


}
