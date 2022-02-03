<?php

namespace App\Entity;

use App\Repository\CodepostalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CodepostalRepository::class)
 */
class Codepostal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=Coordonnees::class, mappedBy="codepostal")
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
            $coordonnee->setCodepostal($this);
        }

        return $this;
    }

    public function removeCoordonnee(Coordonnees $coordonnee): self
    {
        if ($this->coordonnees->removeElement($coordonnee)) {
            // set the owning side to null (unless already changed)
            if ($coordonnee->getCodepostal() === $this) {
                $coordonnee->setCodepostal(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getCode();
    }
}
