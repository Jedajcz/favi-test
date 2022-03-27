<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $partner_id;

    #[ORM\Column(type: 'text')]
    private string $order_id;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $delivery_date;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: Product::class)]
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartnerId(): ?string
    {
        return $this->partner_id;
    }

    public function setPartnerId(string $partner_id): self
    {
        $this->partner_id = $partner_id;

        return $this;
    }

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function setOrderId(string $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getDeliveryDate(): ?DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(DateTimeInterface $delivery_date): self
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setOrder($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            if ($product->getOrder() === $this) {
                $product->setOrder(null);
            }
        }

        return $this;
    }
}
