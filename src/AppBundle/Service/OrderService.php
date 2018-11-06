<?php

namespace AppBundle\Service;

use AppBundle\Cart\Cart;
use AppBundle\Dto\OrderDto;
use AppBundle\Dto\OrderRejectDto;
use AppBundle\Entity\CartItem;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderItem;
use AppBundle\Repository\DeliveryMethodRepository;
use AppBundle\Repository\OrderRepository;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private $em;
    private $cart;
    /** @var ProductRepository */
    private $productRepository;
    /** @var OrderRepository */
    private $orderRepository;
    /** @var UserRepository */
    private $userRepository;
    /** @var DeliveryMethodRepository */
    private $deliveryMethodRepository;

    public function __construct(Cart $cart, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->cart = $cart;
        $this->productRepository = $this->em->getRepository('AppBundle:Product');
        $this->orderRepository = $this->em->getRepository('AppBundle:Order');
        $this->userRepository = $this->em->getRepository('AppBundle:User');
        $this->deliveryMethodRepository = $this->em->getRepository('AppBundle:DeliveryMethod');
    }

    /**
     * @param $userId
     * @param OrderDto $orderDto
     * @return Order
     */
    public function checkout($userId, OrderDto $orderDto): Order
    {
        $user = $this->userRepository->find($userId);
        $deliveryMethod = $this->deliveryMethodRepository->find($orderDto->deliveryMethodId);

        $order = new Order();

        $items = array_map(function (CartItem $item) use ($order) {
            $orderItem = new OrderItem();
            $orderItem->addProduct($item->getProduct());
            $orderItem->setOrder($order);
            $orderItem->setQuantity($item->getQuantity());
            return $orderItem;
        }, $this->cart->getItems());

        foreach ($items as $item) {
            $order->addItem($item);
            $this->em->persist($item);
        }

        $order->addDeliveryMethod($deliveryMethod);
        $order->setUser($user);
        $order->setUserName($orderDto->userName);
        $order->setUserPhone($orderDto->userPhone);
        $orderStatus = $order->addCurrentStatus(Order::NEW);

        $order->setDeliveryIndex($orderDto->deliveryIndex);
        $order->setDeliveryAddress($orderDto->deliveryAddress);
        $order->setNote($orderDto->note);
        $order->setCost($this->cart->getCost());


        $this->em->persist($orderStatus);
        $this->em->persist($order);
        $this->em->flush();
        $this->cart->clear();

        return $order;
    }

    /**
     * Оплата заказа
     * @param $orderId
     */
    public function paid($orderId)
    {
        $order = $this->orderRepository->find($orderId);

        if (!$order->isNew()) {
            throw new \DomainException('Невозможно оплатить заказ');
        }

        $status = $order->paid();
        $this->em->persist($status);
        $this->em->persist($order);
        $this->em->flush();
    }

    /**
     * Отправка заказа
     * @param $orderId
     */
    public function sent($orderId)
    {
        $order = $this->orderRepository->find($orderId);

        if (!$order->isCanSent()) {
            throw new \DomainException('Необходимо оплатить заказ перед отправкой');
        }

        $status = $order->sent();
        $this->em->persist($status);
        $this->em->persist($order);
        $this->em->flush();
    }

    /**
     * Подтверждение получения
     * @param $orderId
     */
    public function complete($orderId)
    {
        $order = $this->orderRepository->find($orderId);

        if (!$order->isCanComplete()) {
            throw new \DomainException('Необходимо отправить товар перед подтверждением');
        }

        $status = $order->completed();
        $this->em->persist($status);
        $this->em->persist($order);
        $this->em->flush();
    }

    /**
     * Отмена заказа
     * @param $orderId
     */
    public function reject($orderId)
    {
        $order = $this->orderRepository->find($orderId);

        if (!$order->isNew()) {
            throw new \DomainException('Невозможно отменить заказ');
        }

        $status = $order->reject();
        $this->em->persist($status);
        $this->em->persist($order);
        $this->em->flush();
    }

    /**
     * Отмена заказа пользователем
     * @param $orderId
     * @param OrderRejectDto $dto
     */
    public function rejectByCustomer($orderId, OrderRejectDto $dto)
    {
        $order = $this->orderRepository->find($orderId);

        if (!$order->isNew()) {
            throw new \DomainException('Невозможно отменить заказ');
        }

        $status = $order->rejectByCustomer($dto->reason);
        $this->em->persist($status);
        $this->em->persist($order);
        $this->em->flush();
    }

    /**
     * Удаление заказа
     * @param $orderId
     */
    public function delete($orderId)
    {
        $order = $this->orderRepository->find($orderId);

        if (!$order->isReject()) {
            throw new \DomainException('Невозможно удалить заказ');
        }

        $this->em->remove($order);
        $this->em->flush();
    }
}