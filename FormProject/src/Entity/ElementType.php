<?php

namespace App\Entity;

use App\Repository\ElementTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass=ElementTypeRepository::class)
 */
class ElementType
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
    private $type;

    
    /**
     * @ORM\Column(type="boolean")
     */
    private $multiple;

    /**
     * @ORM\OneToMany(targetEntity=Element::class, mappedBy="elementType")
     */
    private $element;


    /**
     * @ORM\ManyToMany(targetEntity=ConstraintValidation::class, inversedBy="elementTypes")
     */
    private $constraintValidation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $baliseHtml;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeHtml;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeFr;

    public function __construct()
    {
        $this->element = new ArrayCollection();
        $this->multipleElementTypes = new ArrayCollection();
        $this->constraintValidation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

   

    public function getMultiple(): ?bool
    {
        return $this->multiple;
    }

    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @return Collection|Element[]
     */
    public function getElement(): Collection
    {
        return $this->element;
    }

    public function addElement(Element $element): self
    {
        if (!$this->element->contains($element)) {
            $this->element[] = $element;
            $element->setElementType($this);
        }

        return $this;
    }

    public function removeElement(Element $element): self
    {
        if ($this->element->removeElement($element)) {
            // set the owning side to null (unless already changed)
            if ($element->getElementType() === $this) {
                $element->setElementType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MultipleElementType[]
     */
    public function getMultipleElementTypes(): Collection
    {
        return $this->multipleElementTypes;
    }



    /**
     * @return Collection|ConstraintValidation[]
     */
    public function getConstraintValidation(): Collection
    {
        return $this->constraintValidation;
    }

    public function addConstraintValidation(ConstraintValidation $constraintValidation): self
    {
        if (!$this->constraintValidation->contains($constraintValidation)) {
            $this->constraintValidation[] = $constraintValidation;
        }

        return $this;
    }

    public function removeConstraintValidation(ConstraintValidation $constraintValidation): self
    {
        $this->constraintValidation->removeElement($constraintValidation);

        return $this;
    }

    public function getBaliseHtml(): ?string
    {
        return $this->baliseHtml;
    }

    public function setBaliseHtml(string $baliseHtml): self
    {
        $this->baliseHtml = $baliseHtml;

        return $this;
    }

    public function getTypeHtml(): ?string
    {
        return $this->typeHtml;
    }

    public function setTypeHtml(?string $typeHtml): self
    {
        $this->typeHtml = $typeHtml;

        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(string $icone): self
    {
        $this->icone = $icone;

        return $this;
    }

    public function getTypeFr(): ?string
    {
        return $this->typeFr;
    }

    public function setTypeFr(string $typeFr): self
    {
        $this->typeFr = $typeFr;

        return $this;
    }
    
   
  
}
