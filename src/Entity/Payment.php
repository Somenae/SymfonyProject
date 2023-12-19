<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'payment', targetEntity: Orders::class)]
    private Collection $Orders;

    public function __construct()
    {
        $this->Orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrders(): Collection
    {
        return $this->Orders;
    }

    public function addOrder(Orders $order): static
    {
        if (!$this->Orders->contains($order)) {
            $this->Orders->add($order);
            $order->setPayment($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): static
    {
        if ($this->Orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getPayment() === $this) {
                $order->setPayment(null);
            }
        }

        return $this;
    }
}
