<?php

namespace App\Entity;

use App\Repository\CaisseRepository;
use Doctrine\ORM\Mapping as ORM;
use symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo; 

#[ORM\Entity(repositoryClass: CaisseRepository::class)]
class Caisse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

     //**
     // * @Assert\NotBlank
     // */
    #[ORM\Column(type: 'float')]
    private $montant = 0;

    #[ORM\Column(type: 'float')]
    private $balance = 0;

    #[ORM\Column(type: 'string', length: 255)]
    private $employe;

    #[ORM\Column(type: 'string', length: 255)]
    private $category;

    #[ORM\Column(type: 'datetime_immutable')]

    /**
     * @Gedmo\Timestampable(on="create")
     */
     
    private $createdAt;
    
     /**
     * @Gedmo\Timestampable(on="update")
     */

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    #[ORM\Column(type: 'datetime')]
    private $date;
     /**
    *#[ORM\Column(type: 'string', length: 255)]
    private $docFileName;
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->employe;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }


    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getEmploye(): ?string
    {
        return $this->employe;
    }

    public function setEmploye(string $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

     public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
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


}
