<?php

namespace App\Entity;

use App\Repository\FormDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
     * @var \DateTime $date
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=ElementData::class, mappedBy="formData")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $elementData;

    /**
     * @ORM\Column(type="integer")
     */
    private $refForm;

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

    public function getRefForm(): ?int
    {
        return $this->refForm;
    }

    public function setRefForm(int $refForm): self
    {
        $this->refForm = $refForm;

        return $this;
    }

    public function getCode() {

        $number = sprintf('%04d', $this->id);
        return 'F'.$number;
    }

    public function getUser() {

        $users = ['Ahmed WAHABI', 'Manel Mnif', 'Administrateur'];
        $key = array_rand($users);
        return $users[$key];
    }
}
