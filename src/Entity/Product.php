<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $ProductCategory;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Taxes $ProductTaxes = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CartLine::class)]
    private Collection $ProductCartLine;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Sales $ProductSales = null;

    public function __construct()
    {
        $this->ProductCategory = new ArrayCollection();
        $this->ProductCartLine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getProductCategory(): Collection
    {
        return $this->ProductCategory;
    }

    public function addProductCategory(Category $productCategory): static
    {
        if (!$this->ProductCategory->contains($productCategory)) {
            $this->ProductCategory->add($productCategory);
        }

        return $this;
    }

    public function removeProductCategory(Category $productCategory): static
    {
        $this->ProductCategory->removeElement($productCategory);

        return $this;
    }

    public function getProductTaxes(): ?Taxes
    {
        return $this->ProductTaxes;
    }

    public function setProductTaxes(?Taxes $ProductTaxes): static
    {
        $this->ProductTaxes = $ProductTaxes;

        return $this;
    }

    /**
     * @return Collection<int, CartLine>
     */
    public function getProductCartLine(): Collection
    {
        return $this->ProductCartLine;
    }

    public function addProductCartLine(CartLine $productCartLine): static
    {
        if (!$this->ProductCartLine->contains($productCartLine)) {
            $this->ProductCartLine->add($productCartLine);
            $productCartLine->setProduct($this);
        }

        return $this;
    }

    public function removeProductCartLine(CartLine $productCartLine): static
    {
        if ($this->ProductCartLine->removeElement($productCartLine)) {
            // set the owning side to null (unless already changed)
            if ($productCartLine->getProduct() === $this) {
                $productCartLine->setProduct(null);
            }
        }

        return $this;
    }

    public function getProductSales(): ?Sales
    {
        return $this->ProductSales;
    }

    public function setProductSales(?Sales $ProductSales): static
    {
        $this->ProductSales = $ProductSales;

        return $this;
    }
}
