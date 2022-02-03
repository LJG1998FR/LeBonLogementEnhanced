<?php

namespace App\Entity;

use App\Repository\CommuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommuneRepository::class)
 */
class Commune
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Coordonnees::class, mappedBy="commune")
     */
    private $coordonnees;

    public function __construct()
    {
        $this->coordonnees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Coordonnees[]
     */
    public function getCoordonnees(): Collection
    {
        return $this->coordonnees;
    }

    public function addCoordonnee(Coordonnees $coordonnee): self
    {
        if (!$this->coordonnees->contains($coordonnee)) {
            $this->coordonnees[] = $coordonnee;
            $coordonnee->setCommune($this);
        }

        return $this;
    }

    public function removeCoordonnee(Coordonnees $coordonnee): self
    {
        if ($this->coordonnees->removeElement($coordonnee)) {
            // set the owning side to null (unless already changed)
            if ($coordonnee->getCommune() === $this) {
                $coordonnee->setCommune(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getNom();
    }
}
