<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class OrderItem
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="order_item")
 */
class OrderItem
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $product;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $productName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $productCode;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order", inversedBy="items", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $order;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product): void
    {
        $this->product = $product;
        $this->productName = $product->getName();
        $this->productCode = $product->getCode();
        $this->price = $product->getPrice();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return string
     */
    public function getProductCode(): string
    {
        return $this->productCode;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}