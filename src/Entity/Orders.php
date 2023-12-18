<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column(length: 50)]
    private ?string $client_name = null;

    #[ORM\Column]
    private ?float $total_price = null;

    #[ORM\OneToOne(mappedBy: 'Orders', cascade: ['persist', 'remove'])]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(inversedBy: 'Orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\ManyToOne(inversedBy: 'OrdersShipped')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $addressShipped = null;

    #[ORM\ManyToOne(inversedBy: 'billingAddress')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $billingAddress = null;

    #[ORM\ManyToOne(inversedBy: 'Orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderState $orderState = null;

    #[ORM\ManyToOne(inversedBy: 'Orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Payment $payment = null;

    #[ORM\ManyToOne(inversedBy: 'Orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Shipping $shipping = null;

    #[ORM\OneToMany(mappedBy: 'Orders', targetEntity: OrderLine::class)]
    private Collection $orderLines;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): static
    {
        $this->client_name = $client_name;

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

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        // unset the owning side of the relation if necessary
        if ($cart === null && $this->cart !== null) {
            $this->cart->setOrders(null);
        }

        // set the owning side of the relation if necessary
        if ($cart !== null && $cart->getOrders() !== $this) {
            $cart->setOrders($this);
        }

        $this->cart = $cart;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getAddressShipped(): ?Address
    {
        return $this->addressShipped;
    }

    public function setAddressShipped(?Address $addressShipped): static
    {
        $this->addressShipped = $addressShipped;

        return $this;
    }

    public function getBillingAddress(): ?Address
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?Address $billingAddress): static
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getOrderState(): ?OrderState
    {
        return $this->orderState;
    }

    public function setOrderState(?OrderState $orderState): static
    {
        $this->orderState = $orderState;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getShipping(): ?Shipping
    {
        return $this->shipping;
    }

    public function setShipping(?Shipping $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * @return Collection<int, OrderLine>
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): static
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines->add($orderLine);
            $orderLine->setOrders($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): static
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getOrders() === $this) {
                $orderLine->setOrders(null);
            }
        }

        return $this;
    }
}
