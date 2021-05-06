<?php

namespace App\Entity;

use App\Repository\ConstraintValidationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConstraintValidationRepository::class)
 */
class ConstraintValidation
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
    private $contrainte;

    /**
     * @ORM\OneToMany(targetEntity=ConstraintValidationElement::class, mappedBy="constraintValidation")
     */
    private $constraintElement;

    /**
     * @ORM\ManyToMany(targetEntity=ElementType::class, mappedBy="constraintValidation")
     */
    private $elementTypes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $html;

    public function __construct()
    {
        $this->constraintElement = new ArrayCollection();
        $this->elementTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContrainte(): ?string
    {
        return $this->contrainte;
    }

    public function setContrainte(string $contrainte): self
    {
        $this->contrainte = $contrainte;

        return $this;
    }

    /**
     * @return Collection|ConstraintValidationElement[]
     */
    public function getConstraintElement(): Collection
    {
        return $this->constraintElement;
    }

    public function addConstraintElement(ConstraintValidationElement $constraintElement): self
    {
        if (!$this->constraintElement->contains($constraintElement)) {
            $this->constraintElement[] = $constraintElement;
            $constraintElement->setConstraintValidation($this);
        }

        return $this;
    }

    public function removeConstraintElement(ConstraintValidationElement $constraintElement): self
    {
        if ($this->constraintElement->removeElement($constraintElement)) {
            // set the owning side to null (unless already changed)
            if ($constraintElement->getConstraintValidation() === $this) {
                $constraintElement->setConstraintValidation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ElementType[]
     */
    public function getElementTypes(): Collection
    {
        return $this->elementTypes;
    }

    public function addElementType(ElementType $elementType): self
    {
        if (!$this->elementTypes->contains($elementType)) {
            $this->elementTypes[] = $elementType;
            $elementType->addConstraintValidation($this);
        }

        return $this;
    }

    public function removeElementType(ElementType $elementType): self
    {
        if ($this->elementTypes->removeElement($elementType)) {
            $elementType->removeConstraintValidation($this);
        }

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }
}
