<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; //vérifications pour la validation des champs

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Advert", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date d'arrivée doit être au bon format")
     * @Assert\GreaterThan("today", message="La date de prise doit être ultérieure à la date d'aujourd'hui")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date de départ doit être au bon format")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date de restitution doit être supérieure à la date d'aujourd'hui")
     */

    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Callback appelé à chaque fois qu'on créée une réservation
     * 
     * @ORM\PrePersist
     */

    public function prePersist(){
        if(empty($this->createdAt)){
            $this->createdAt = new \Datetime();
        }

        if(empty($this->amount)){
            //prix de l'annonce * nb de jours
            $this->amount = $this->advert->getPrice() * $this->getDuration();
        }
    }

    public function isBookableDates(){
        //dates déjà prises:
         $notAvailableDays = $this->advert->getNotAvailableDays();
                //comparaison entre les dates choisies et les dates déjà pris
        $bookingDays = $this->getDays();

        //Tableau des chaines de caractères de mes journées
        $days = array_map(function($day){
            return $day->format('Y-m-d');
        }, $bookingDays);

        $notAvailable = array_map(function($day){
            return $day->format('Y-m-d');
        }, $notAvailableDays);

        foreach($days as $day){
            if(array_search($day, $notAvailable) !== false) return false;
        }
        return true;
    }
    /**
     * Permet de récupérer un tableau des journées qui correspondent à ma réservation
     *
     * @return array un tableau d'objets DateTime représentant les jours de la réservation
     */
    public function getDays(){
        $result = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 *60 * 60
        );

        $days = array_map(function($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $result);
        return $days;
    }

    /**
     * Permet de récupérer un tableau des jours qui correspondent à la Résa en cours
     *
     * @return array un tableau d'objets DateTime représentant les jours de la résa
     */
    public function getResaDays(){
        $result = range(
            $this->startDate->getTimestamp(), 
            $this->getEndDate->getTimestamp(),
            24 * 60 * 60
        );

        $days = array_map(function($dayTimestamp){
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $result);
        
        return $days;
    }

    public function getDuration(){
        //methode diff des objets DateTime:fait la diff entre 2 dates renvoie un DateInterval
        $diff = $this->endDate->diff($this->startDate);
            return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAdvert(): ?Advert
    {
        return $this->advert;
    }

    public function setAdvert(?Advert $advert): self
    {
        $this->advert = $advert;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
