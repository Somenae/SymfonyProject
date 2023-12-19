<?php

namespace App\Entity;

use App\Repository\OrderLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderLineRepository::class)]
class OrderLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $products_name = null;

    #[ORM\Column]
    private ?float $product_unit_price = null;

    #[ORM\Column]
    private ?float $taxe = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $total_price = null;

    #[ORM\Column]
    private ?float $sales = null;

    #[ORM\ManyToOne(inversedBy: 'orderLines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Orders $Orders = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductsName(): ?string
    {
        return $this->products_name;
    }

    public function setProductsName(string $products_name): static
    {
        $this->products_name = $products_name;

        return $this;
    }

    public function getProductUnitPrice(): ?float
    {
        return $this->product_unit_price;
    }

    public function setProductUnitPrice(float $product_unit_price): static
    {
        $this->product_unit_price = $product_unit_price;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(float $taxe): static
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->total_price;
    }

    public function setTotalPrice(float $total_price): static
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getSales(): ?float
    {
        return $this->sales;
    }

    public function setSales(float $sales): static
    {
        $this->sales = $sales;

        return $this;
    }

    public function getOrders(): ?Orders
    {
        return $this->Orders;
    }

    public function setOrders(?Orders $Orders): static
    {
        $this->Orders = $Orders;

        return $this;
    }
}
