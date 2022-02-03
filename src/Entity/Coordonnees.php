<?php

namespace App\Entity;

use App\Repository\CoordonneesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoordonneesRepository::class)
 */
class Coordonnees
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Codepostal::class, inversedBy="coordonnees")
     */
    private $codepostal;

    /**
     * @ORM\ManyToOne(targetEntity=Commune::class, inversedBy="coordonnees")
     */
    private $commune;

    /**
     * @ORM\OneToMany(targetEntity=Bien::class, mappedBy="coordonnees")
     */
    private $biens;

    public function __construct()
    {
        $this->biens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodepostal(): ?Codepostal
    {
        return $this->codepostal;
    }

    public function setCodepostal(?Codepostal $codepostal): self
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    public function getCommune(): ?Commune
    {
        return $this->commune;
    }

    public function setCommune(?Commune $commune): self
    {
        $this->commune = $commune;

        return $this;
    }
    public function __toString(){
        return $this->getCodepostal().' - '.$this->getCommune();
    }

    /**
     * @return Collection|Bien[]
     */
    public function getBiens(): Collection
    {
        return $this->biens;
    }

    public function addBien(Bien $bien): self
    {
        if (!$this->biens->contains($bien)) {
            $this->biens[] = $bien;
            $bien->setCoordonnees($this);
        }

        return $this;
    }

    public function removeBien(Bien $bien): self
    {
        if ($this->biens->removeElement($bien)) {
            // set the owning side to null (unless already changed)
            if ($bien->getCoordonnees() === $this) {
                $bien->setCoordonnees(null);
            }
        }

        return $this;
    }
}
