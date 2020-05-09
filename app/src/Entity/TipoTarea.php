<?php

namespace App\Entity;

use App\Repository\TipoTareaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoTareaRepository::class)
 */
class TipoTarea
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
    private $codigo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tieneOpciones;

    /**
     * @ORM\ManyToMany(targetEntity=TipoGrafico::class)
     */
    private $tiposGrafico;

    public function __construct()
    {
        $this->tiposGrafico = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getTieneOpciones(): ?bool
    {
        return $this->tieneOpciones;
    }

    public function setTieneOpciones(bool $tieneOpciones): self
    {
        $this->tieneOpciones = $tieneOpciones;

        return $this;
    }

    /**
     * @return Collection|TipoGrafico[]
     */
    public function getTiposGrafico(): Collection
    {
        return $this->tiposGrafico;
    }

    public function addTipoGrafico(TipoGrafico $tipoGrafico): self
    {
        if (!$this->tiposGrafico->contains($tipoGrafico)) {
            $this->tiposGrafico[] = $tipoGrafico;
        }

        return $this;
    }

    public function removeTipoGrafico(TipoGrafico $tipoGrafico): self
    {
        if ($this->tiposGrafico->contains($tipoGrafico)) {
            $this->tiposGrafico->removeElement($tipoGrafico);
        }

        return $this;
    }
}
