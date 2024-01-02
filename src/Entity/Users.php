<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity('email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $role = [];

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(null,'Ce champ doit être rempli')]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(null,'Ce champ doit être rempli')]
    private ?string $lastname = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(null,'Ce champ doit être rempli')]
    #[Assert\Email([
        'message' => 'Veuillez saisir une adresse mail valide',]
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(null,'Ce champ doit être rempli')]
    // #[Assert\PasswordStrength([
    //     'minScore' => PasswordStrength::STRENGTH_WEAK,
    //     'message' => 'Le mot de passe est trop faible',]
    // )]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Cart::class)]
    private Collection $carts;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Address $address = null;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Orders::class)]
    private Collection $Orders;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->Orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        
    }

    public function getUserIdentifier(): string
    {

        return $this->email;
    }
    public function getRoles(): array
    {
        $roles = $this->role;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setUser($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getUser() === $this) {
                $cart->setUser(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

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
            $order->setUsers($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): static
    {
        if ($this->Orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUsers() === $this) {
                $order->setUsers(null);
            }
        }

        return $this;
    }
}
