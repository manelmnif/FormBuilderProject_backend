<?php

namespace App\Entity;

use App\Repository\FormDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormDataRepository::class)
 */
class FormData
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
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=ElementData::class, mappedBy="formData")
     */
    private $elementData;

    public function __construct()
    {
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
            $elementData->setFormData($this);
        }

        return $this;
    }

    public function removeElementData(ElementData $elementData): self
    {
        if ($this->elementData->removeElement($elementData)) {
            // set the owning side to null (unless already changed)
            if ($elementData->getFormData() === $this) {
                $elementData->setFormData(null);
            }
        }

        return $this;
    }
}
