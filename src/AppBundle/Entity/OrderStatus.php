<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class OrderStatus
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="order_status")
 */
class OrderStatus
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="status", type="string")
     */
    private $value;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order", inversedBy="status", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $order;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(string $value, \DateTime $dateTime)
    {
        $this->value = $value;
        $this->createdAt = $dateTime;
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
    public function getValue(): string
    {
        return $this->value;
    }

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
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}