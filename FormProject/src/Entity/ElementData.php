<?php

namespace App\Entity;

use App\Repository\ElementDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElementDataRepository::class)
 */
class ElementData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Element::class, inversedBy="elementData")
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=ElementDataMultiple::class, mappedBy="elementData")
     */
    private $elementDataMultiples;

    /**
     * @ORM\ManyToOne(targetEntity=FormData::class, inversedBy="elementData")
     */
    private $formData;

    public function __construct()
    {
        $this->elementDataMultiples = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?Element
    {
        return $this->value;
    }

    public function setValue(?Element $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|ElementDataMultiple[]
     */
    public function getElementDataMultiples(): Collection
    {
        return $this->elementDataMultiples;
    }

    public function addElementDataMultiple(ElementDataMultiple $elementDataMultiple): self
    {
        if (!$this->elementDataMultiples->contains($elementDataMultiple)) {
            $this->elementDataMultiples[] = $elementDataMultiple;
            $elementDataMultiple->setElementData($this);
        }

        return $this;
    }

    public function removeElementDataMultiple(ElementDataMultiple $elementDataMultiple): self
    {
        if ($this->elementDataMultiples->removeElement($elementDataMultiple)) {
            // set the owning side to null (unless already changed)
            if ($elementDataMultiple->getElementData() === $this) {
                $elementDataMultiple->setElementData(null);
            }
        }

        return $this;
    }

    public function getFormData(): ?FormData
    {
        return $this->formData;
    }

    public function setFormData(?FormData $formData): self
    {
        $this->formData = $formData;

        return $this;
    }
}
