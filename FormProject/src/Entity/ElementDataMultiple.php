<?php

namespace App\Entity;

use App\Repository\ElementDataMultipleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElementDataMultipleRepository::class)
 */
class ElementDataMultiple
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
     * @ORM\ManyToOne(targetEntity=ElementData::class, inversedBy="elementDataMultiples")
     */
    private $elementData;

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

    public function getElementData(): ?ElementData
    {
        return $this->elementData;
    }

    public function setElementData(?ElementData $elementData): self
    {
        $this->elementData = $elementData;

        return $this;
    }
}
