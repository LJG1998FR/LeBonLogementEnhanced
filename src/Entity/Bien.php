<?php

namespace App\Entity;

use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=BienRepository::class)
 */
class Bien
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $superficie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_de_depot;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="biens")
     */
    private $proprietaire;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="bien",cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=Coordonnees::class, inversedBy="biens")
     */
    private $coordonnees;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getSuperficie(): ?string
    {
        return $this->superficie;
    }

    public function setSuperficie(string $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getDateDeDepot(): ?\DateTimeInterface
    {
        return $this->date_de_depot;
    }

    public function setDateDeDepot(\DateTimeInterface $date_de_depot): self
    {
        $this->date_de_depot = $date_de_depot;

        return $this;
    }

    public function __construct(){
        $this->date_de_depot=new \Datetime;
        $this->images = new ArrayCollection();
    }
    public function __toString(){
        return $this->getId();
    }

    public function getProprietaire(): ?Utilisateur
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Utilisateur $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

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
            $image->setBien($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBien() === $this) {
                $image->setBien(null);
            }
        }

        return $this;
    }

    public function getCoordonnees(): ?Coordonnees
    {
        return $this->coordonnees;
    }

    public function setCoordonnees(?Coordonnees $coordonnees): self
    {
        $this->coordonnees = $coordonnees;

        return $this;
    }
}
