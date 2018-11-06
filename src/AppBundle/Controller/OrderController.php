<?php

namespace AppBundle\Controller;

use AppBundle\Dto\OrderDto;
use AppBundle\Entity\Order;
use AppBundle\Form\OrderCreateType;
use AppBundle\Form\OrderRejectType;
use AppBundle\Service\CartService;
use AppBundle\Service\OrderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class OrderController
 * @package AppBundle\Controller
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class OrderController extends Controller
{
    private $orderService;
    private $cartService;

    public function __construct
    (
        OrderService $orderService,
        CartService $cartService
    )
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/order", name="order_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $orderList = $em->getRepository('AppBundle:Order')->getAllByUser($user->getId());

        return $this->render('order/index.html.twig', [
            'orderList' => $orderList,
        ]);

    }

    /**
     * @Route("/order/{id}", requirements={"id": "\d+"}, name="order_view")
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id)
    {
        $order = $this->getOrderByUser($id);

        $rejectForm = $this->createForm(OrderRejectType::class);

        return $this->render('order/view.html.twig', [
            'order' => $order,
            'rejectForm' => $rejectForm->createView(),
        ]);
    }

    /**
     * @Route("/order/checkout", name="order_checkout")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkoutAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cart = $this->cartService->getCart();
        $deliveryMethodList = $em->getRepository('AppBundle:DeliveryMethod')->findAll();
        $checkoutForm = $this->createForm(OrderCreateType::class, new OrderDto(), [
            'deliveryMethodList' => $deliveryMethodList,
        ]);

        $checkoutForm->handleRequest($request);
        if ($checkoutForm->isValid()) {
            try {
                $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
                $order = $this->orderService->checkout($userId, $checkoutForm->getData());
                $this->addFlash('success', 'Заказ создан');
                return $this->redirectToRoute('order_view', ['id' => $order->getId()]);
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('order_checkout');
            }
        }

        return $this->render('order/checkout.html.twig', [
            'checkoutForm' => $checkoutForm->createView(),
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/order/paid/{id}", requirements={"id": "\d+"}, name="order_paid")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function paidAction(Request $request, $id)
    {
        if (!$this->isCsrfTokenValid('paid', $request->request->get('token'))) {
            return $this->redirectToRoute('order_index');
        }

        $order = $this->getOrderByUser($id);

        try {
            $this->orderService->paid($order->getId());
            $this->addFlash('success', 'Заказ оплачен');
            return $this->redirectToRoute('order_view', ['id' => $id]);
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('order_view', ['id' => $id]);
        }
    }

    /**
     * @Route("/order/complete/{id}", requirements={"id": "\d+"}, name="order_complete")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function completeAction(Request $request, $id)
    {
        if (!$this->isCsrfTokenValid('complete', $request->request->get('token'))) {
            return $this->redirectToRoute('order_index');
        }

        $order = $this->getOrderByUser($id);

        try {
            $this->orderService->complete($order->getId());
            $this->addFlash('success', 'Доставка подтверждена');
            return $this->redirectToRoute('order_view', ['id' => $id]);
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('order_view', ['id' => $id]);
        }
    }

    /**
     * @Route("/order/reject/{id}", requirements={"id": "\d+"}, name="order_reject")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function rejectAction(Request $request, $id)
    {
        $order = $this->getOrderByUser($id);

        $rejectForm = $this->createForm(OrderRejectType::class);
        $rejectForm->handleRequest($request);
        if ($rejectForm->isValid()) {
            try {
                $this->orderService->rejectByCustomer($order->getId(), $rejectForm->getData());
                $this->addFlash('success', 'Заказ отменен');
                return $this->redirectToRoute('order_view', ['id' => $id]);
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('order_view', ['id' => $id]);
            }
        }
        return $this->redirectToRoute('order_view', ['id' => $id]);
    }

    /**
     * @param $id
     * @return Order
     */
    private function getOrderByUser($id): Order
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        return $em->getRepository('AppBundle:Order')->getByUser($id, $userId);
    }
}