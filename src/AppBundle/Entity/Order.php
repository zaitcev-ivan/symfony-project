<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Order
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 * @ORM\Table(name="order_table")
 */
class Order
{
    const NEW = 'new';
    const PAID = 'paid';
    const SENT = 'sent';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
    const CANCELLED_BY_CUSTOMER = 'cancelled_by_customer';

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $userName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $userPhone;

    /**
     * @var DeliveryMethod
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\DeliveryMethod")
     * @ORM\JoinColumn(name="delivery_method_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $deliveryMethod;

    /**
     * @var string
     * @ORM\Column(name="delivery_method_name", type="string")
     */
    private $deliveryMethodName;

    /**
     * @var integer
     * @ORM\Column(name="delivery_method_cost", type="integer")
     */
    private $deliveryMethodCost;

    /**
     * @var string
     * @ORM\Column(name="payment_method", type="string", nullable=true)
     */
    private $paymentMethod;

    /**
     * @var integer
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost;

    /**
     * @var string
     * @ORM\Column(name="note", type="string", nullable=true)
     */
    private $note;

    /**
     * @var string
     * @ORM\Column(name="current_status", type="string")
     */
    private $currentStatus;

    /**
     * @var OrderStatus[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderStatus", mappedBy="order")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(name="cancel_reason", type="string", nullable=true)
     */
    private $cancelReason;

    /**
     * @var string
     * @ORM\Column(name="delivery_index", type="string", nullable=true)
     */
    private $deliveryIndex;

    /**
     * @var string
     * @ORM\Column(name="delivery_address", type="text", nullable=true)
     */
    private $deliveryAddress;

    /**
     * @var OrderItem[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderItem", mappedBy="order")
     */
    private $items;

    public function __construct()
    {
        $this->status = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->currentStatus === Order::NEW;
    }

    public function isPaid()
    {
        return $this->currentStatus === Order::PAID;
    }

    public function isSent()
    {
        return $this->currentStatus === Order::SENT;
    }

    public function isCanSent()
    {
        return $this->isPaid();
    }

    public function isCanComplete()
    {
        return $this->isSent();
    }

    public function isReject()
    {
        return $this->currentStatus === Order::CANCELLED;
    }

    /**
     * Отмена заказа администратором
     */
    public function reject()
    {
        return $this->setOrderStatus(Order::CANCELLED);
    }

    /**
     * Отмена заказа пользователем
     * @param string $reason
     * @return OrderStatus
     */
    public function rejectByCustomer($reason = '')
    {
        $this->setCancelReason($reason);
        return $this->setOrderStatus(Order::CANCELLED_BY_CUSTOMER);
    }

    /**
     * Оплата заказа
     * @return OrderStatus
     */
    public function paid()
    {
        return $this->setOrderStatus(Order::PAID);
    }

    /**
     * Отправка заказа
     * @return OrderStatus
     */
    public function sent()
    {
        return $this->setOrderStatus(Order::SENT);
    }

    public function completed()
    {
        return $this->setOrderStatus(Order::COMPLETED);
    }

    private function setOrderStatus($currentStatus, \DateTime $dateTime = null)
    {
        $this->currentStatus = $currentStatus;
        $orderStatus = new OrderStatus($this->currentStatus, $dateTime ?: new \DateTime());
        $orderStatus->setOrder($this);
        $this->status->add($orderStatus);
        return $orderStatus;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return DeliveryMethod
     */
    public function getDeliveryMethod(): DeliveryMethod
    {
        return $this->deliveryMethod;
    }

    /**
     * @param DeliveryMethod $deliveryMethod
     */
    public function addDeliveryMethod(DeliveryMethod $deliveryMethod): void
    {
        $this->deliveryMethod = $deliveryMethod;
        $this->deliveryMethodName = $deliveryMethod->getName();
        $this->deliveryMethodCost = $deliveryMethod->getCost();
    }

    /**
     * @return string
     */
    public function getDeliveryMethodName(): string
    {
        return $this->deliveryMethodName;
    }

    /**
     * @return int
     */
    public function getDeliveryMethodCost(): int
    {
        return $this->deliveryMethodCost;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return string
     */
    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    /**
     * @return string
     */
    public function getCancelReason(): string
    {
        return $this->cancelReason;
    }

    /**
     * @return OrderStatus[]
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUserPhone(): string
    {
        return $this->userPhone;
    }

    /**
     * @param string $userPhone
     */
    public function setUserPhone(string $userPhone): void
    {
        $this->userPhone = $userPhone;
    }

    /**
     * @param string $currentStatus
     * @return OrderStatus
     */
    public function addCurrentStatus(string $currentStatus): OrderStatus
    {
        $this->currentStatus = $currentStatus;
        $orderStatus = new OrderStatus($currentStatus, new \DateTime());
        $orderStatus->setOrder($this);
        $this->status->add($orderStatus);
        return $orderStatus;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderItem $orderItem
     */
    public function addItem(OrderItem $orderItem): void
    {
        $this->items->add($orderItem);
    }

    /**
     * @return string
     */
    public function getDeliveryIndex(): string
    {
        return $this->deliveryIndex;
    }

    /**
     * @param string $deliveryIndex
     */
    public function setDeliveryIndex(string $deliveryIndex): void
    {
        $this->deliveryIndex = $deliveryIndex;
    }

    /**
     * @return string
     */
    public function getDeliveryAddress(): string
    {
        return $this->deliveryAddress;
    }

    /**
     * @param string $deliveryAddress
     */
    public function setDeliveryAddress(string $deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note = null): void
    {
        $this->note = $note;
    }

    /**
     * @param int $cost
     */
    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @param string $cancelReason
     */
    public function setCancelReason(string $cancelReason = ''): void
    {
        $this->cancelReason = $cancelReason;
    }
}