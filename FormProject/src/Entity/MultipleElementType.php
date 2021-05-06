<?php

namespace App\Entity;

use App\Repository\MultipleElementTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MultipleElementTypeRepository::class)
 */
class MultipleElementType
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
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=ElementType::class, inversedBy="multipleElementTypes")
     */
    private $elementType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getElementType(): ?ElementType
    {
        return $this->elementType;
    }

    public function setElementType(?ElementType $elementType): self
    {
        $this->elementType = $elementType;

        return $this;
    }
}
