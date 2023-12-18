<?php

namespace App\Entity;

use App\Repository\SalesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalesRepository::class)]
class Sales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $amount_percentage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmountPercentage(): ?float
    {
        return $this->amount_percentage;
    }

    public function setAmountPercentage(?float $amount_percentage): static
    {
        $this->amount_percentage = $amount_percentage;

        return $this;
    }
}
