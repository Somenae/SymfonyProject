<?php

namespace App\Entity;

use App\Repository\ShippingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShippingRepository::class)]
class Shipping
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $company = null;

    #[ORM\Column(length: 50)]
    private ?string $transport_type = null;

    #[ORM\Column]
    private ?float $shipping_price = null;

    #[ORM\OneToMany(mappedBy: 'shipping', targetEntity: orders::class)]
    private Collection $Orders;

    public function __construct()
    {
        $this->Orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getTransportType(): ?string
    {
        return $this->transport_type;
    }

    public function setTransportType(string $transport_type): static
    {
        $this->transport_type = $transport_type;

        return $this;
    }

    public function getShippingPrice(): ?float
    {
        return $this->shipping_price;
    }

    public function setShippingPrice(float $shipping_price): static
    {
        $this->shipping_price = $shipping_price;

        return $this;
    }

    /**
     * @return Collection<int, orders>
     */
    public function getOrders(): Collection
    {
        return $this->Orders;
    }

    public function addOrder(orders $order): static
    {
        if (!$this->Orders->contains($order)) {
            $this->Orders->add($order);
            $order->setShipping($this);
        }

        return $this;
    }

    public function removeOrder(orders $order): static
    {
        if ($this->Orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getShipping() === $this) {
                $order->setShipping(null);
            }
        }

        return $this;
    }
}
