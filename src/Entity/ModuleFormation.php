<?php

namespace App\Entity;

use App\Repository\ModuleFormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleFormationRepository::class)
 */
class ModuleFormation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $intitule;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="moduleFormations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Programme::class, mappedBy="moduleFormation", orphanRemoval=true)
     */
    private $programmes;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes[] = $programme;
            $programme->setModuleFormation($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): self
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getModuleFormation() === $this) {
                $programme->setModuleFormation(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->intitule;
    }
}
