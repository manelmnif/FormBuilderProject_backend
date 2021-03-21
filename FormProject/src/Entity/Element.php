<?php

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElementRepository::class)
 */
class Element
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
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $placeholder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isrequired;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tooltype;

    /**
     * @ORM\ManyToOne(targetEntity=Bloc::class, inversedBy="elements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bloc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getIsrequired(): ?bool
    {
        return $this->isrequired;
    }

    public function setIsrequired(bool $isrequired): self
    {
        $this->isrequired = $isrequired;

        return $this;
    }

    public function getTooltype(): ?string
    {
        return $this->tooltype;
    }

    public function setTooltype(string $tooltype): self
    {
        $this->tooltype = $tooltype;

        return $this;
    }

    public function getBloc(): ?Bloc
    {
        return $this->bloc;
    }

    public function setBloc(?Bloc $bloc): self
    {
        $this->bloc = $bloc;

        return $this;
    }
}
