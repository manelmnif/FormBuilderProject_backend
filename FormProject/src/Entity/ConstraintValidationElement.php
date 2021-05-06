<?php

namespace App\Entity;

use App\Repository\ConstraintValidationElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConstraintValidationElementRepository::class)
 */
class ConstraintValidationElement
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
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Element::class, inversedBy="constraintValidationElements")
     */
    private $element;

    /**
     * @ORM\ManyToOne(targetEntity=ConstraintValidation::class, inversedBy="constraintElement")
     */
    private $constraintValidation;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getElement(): ?Element
    {
        return $this->element;
    }

    public function setElement(?Element $element): self
    {
        $this->element = $element;

        return $this;
    }

    public function getConstraintValidation(): ?ConstraintValidation
    {
        return $this->constraintValidation;
    }

    public function setConstraintValidation(?ConstraintValidation $constraintValidation): self
    {
        $this->constraintValidation = $constraintValidation;

        return $this;
    }
}
