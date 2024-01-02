<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Users::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'addressShipped', targetEntity: Orders::class)]
    private Collection $OrdersShipped;

    #[ORM\OneToMany(mappedBy: 'billingAddress', targetEntity: Orders::class)]
    private Collection $billingAddress;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->OrdersShipped = new ArrayCollection();
        $this->billingAddress = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setAddress($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAddress() === $this) {
                $user->setAddress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrdersShipped(): Collection
    {
        return $this->OrdersShipped;
    }

    public function addOrdersShipped(Orders $ordersShipped): static
    {
        if (!$this->OrdersShipped->contains($ordersShipped)) {
            $this->OrdersShipped->add($ordersShipped);
            $ordersShipped->setAddressShipped($this);
        }

        return $this;
    }

    public function removeOrdersShipped(Orders $ordersShipped): static
    {
        if ($this->OrdersShipped->removeElement($ordersShipped)) {
            // set the owning side to null (unless already changed)
            if ($ordersShipped->getAddressShipped() === $this) {
                $ordersShipped->setAddressShipped(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getBillingAddress(): Collection
    {
        return $this->billingAddress;
    }

    public function addBillingAddress(Orders $billingAddress): static
    {
        if (!$this->billingAddress->contains($billingAddress)) {
            $this->billingAddress->add($billingAddress);
            $billingAddress->setBillingAddress($this);
        }

        return $this;
    }

    public function removeBillingAddress(Orders $billingAddress): static
    {
        if ($this->billingAddress->removeElement($billingAddress)) {
            // set the owning side to null (unless already changed)
            if ($billingAddress->getBillingAddress() === $this) {
                $billingAddress->setBillingAddress(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->address;
    }
}
