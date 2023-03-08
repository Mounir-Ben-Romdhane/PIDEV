<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as assert;


#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[Groups("reclamation:read")]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[assert\NotBlank(message:"type should not be blank")]
    #[Groups("reclamation:read")]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[assert\NotBlank(message:"message should not be blank")]
    #[Groups("reclamation:read")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("reclamation:read")]
    private ?\DateTimeInterface $date_reclamation = null;

    #[ORM\OneToMany(mappedBy: 'reclamation', targetEntity: Reponse::class,cascade:["remove"])]
    private Collection $reponses;

    #[ORM\Column(length: 255)]
    #[assert\NotBlank(message:"name should not be blank")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $email_connecte = null;

    #[ORM\Column(length: 255)]

    #[assert\Email()]
    private ?string $email_reclamation = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;


    public function __construct()
    {
        $this->reponses = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->date_reclamation;
    }

    public function setDateReclamation(\DateTimeInterface $date_reclamation): self
    {
        $this->date_reclamation = $date_reclamation;

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setReclamation($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getReclamation() === $this) {
                $reponse->setReclamation(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    public function getEmailConnecte(): ?string
    {
        return $this->email_connecte;
    }

    public function setEmailConnecte(string $email_connecte): self
    {
        $this->email_connecte = $email_connecte;

        return $this;
    }

    public function getEmailReclamation(): ?string
    {
        return $this->email_reclamation;
    }

    public function setEmailReclamation(string $email_reclamation): self
    {
        $this->email_reclamation = $email_reclamation;

        return $this;
    }

    public function getEmailConnecte(): ?string
    {
        return $this->email_connecte;
    }

    public function setEmailConnecte(string $email_connecte): self
    {
        $this->email_connecte = $email_connecte;

        return $this;
    }

    public function getEmailReclamation(): ?string
    {
        return $this->email_reclamation;
    }

    public function setEmailReclamation(string $email_reclamation): self
    {
        $this->email_reclamation = $email_reclamation;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }


}
