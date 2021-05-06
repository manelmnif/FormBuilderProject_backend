<?php

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeholder;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isrequired;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="elements")
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity=ElementType::class, inversedBy="element")
     */
    private $elementType;

    /**
     * @ORM\OneToMany(targetEntity=ConstraintValidationElement::class, mappedBy="element")
     */
    private $constraintValidationElements;

    /**
     * @ORM\OneToMany(targetEntity=ElementData::class, mappedBy="value")
     */
    private $elementData;

    public function __construct()
    {
        $this->constraintValidationElements = new ArrayCollection();
        $this->elementData = new ArrayCollection();
    }

  

 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

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

    /**
     * @return Collection|ConstraintValidationElement[]
     */
    public function getConstraintValidationElements(): Collection
    {
        return $this->constraintValidationElements;
    }

    public function addConstraintValidationElement(ConstraintValidationElement $constraintValidationElement): self
    {
        if (!$this->constraintValidationElements->contains($constraintValidationElement)) {
            $this->constraintValidationElements[] = $constraintValidationElement;
            $constraintValidationElement->setElement($this);
        }

        return $this;
    }

    public function removeConstraintValidationElement(ConstraintValidationElement $constraintValidationElement): self
    {
        if ($this->constraintValidationElements->removeElement($constraintValidationElement)) {
            // set the owning side to null (unless already changed)
            if ($constraintValidationElement->getElement() === $this) {
                $constraintValidationElement->setElement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ElementData[]
     */
    public function getElementData(): Collection
    {
        return $this->elementData;
    }

    public function addElementData(ElementData $elementData): self
    {
        if (!$this->elementData->contains($elementData)) {
            $this->elementData[] = $elementData;
            $elementData->setValue($this);
        }

        return $this;
    }

    public function removeElementData(ElementData $elementData): self
    {
        if ($this->elementData->removeElement($elementData)) {
            // set the owning side to null (unless already changed)
            if ($elementData->getValue() === $this) {
                $elementData->setValue(null);
            }
        }

        return $this;
    }

 

  



  


}
