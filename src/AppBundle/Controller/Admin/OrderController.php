<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Order;
use AppBundle\Service\OrderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderController
 * @package AppBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @Route("/admin/order", name="admin_order_list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $orderList = $em->getRepository('AppBundle:Order')->findAll();

        return $this->render('admin/order/index.html.twig', [
            'orderList' => $orderList,
        ]);
    }

    /**
     * @Route("/admin/order/{id}", requirements={"id": "\d+"}, name="admin_order_view")
     * @param Order $order
     * @return Response
     */
    public function viewAction(Order $order)
    {
        return $this->render('admin/order/view.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * @Route("/admin/order/paid/{id}", requirements={"id": "\d+"}, name="admin_order_paid")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function paidAction(Request $request, $id)
    {
        if (!$this->isCsrfTokenValid('paid', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_order_list');
        }
        try {
            $this->orderService->paid($id);
            $this->addFlash('success', 'Заказ оплачен');
            return $this->redirectToRoute('admin_order_view', ['id' => $id]);
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin_order_view', ['id' => $id]);
        }
    }

    /**
     * @Route("/admin/order/sent/{id}", requirements={"id": "\d+"}, name="admin_order_sent")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function sentAction(Request $request, $id)
    {
        if (!$this->isCsrfTokenValid('paid', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_order_list');
        }
        try {
            $this->orderService->sent($id);
            $this->addFlash('success', 'Заказ отправлен');
            return $this->redirectToRoute('admin_order_view', ['id' => $id]);
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin_order_view', ['id' => $id]);
        }
    }

    /**
     * @Route("/admin/order/complete/{id}", requirements={"id": "\d+"}, name="admin_order_complete")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function completeAction(Request $request, $id)
    {
        if (!$this->isCsrfTokenValid('complete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_order_list');
        }
        try {
            $this->orderService->complete($id);
            $this->addFlash('success', 'Заказ подтвержден');
            return $this->redirectToRoute('admin_order_view', ['id' => $id]);
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin_order_view', ['id' => $id]);
        }
    }

    /**
     * @Route("/admin/order/delete/{id}", requirements={"id": "\d+"}, name="admin_order_delete")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_order_list');
        }
        try {
            $this->orderService->delete($id);
            $this->addFlash('success', 'Заказ удален');
            return $this->redirectToRoute('admin_order_list');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin_order_list');
        }
    }

    /**
     * @Route("/admin/order/reject/{id}", requirements={"id": "\d+"}, name="admin_order_reject")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function rejectAction(Request $request, $id)
    {
        if (!$this->isCsrfTokenValid('reject', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_order_list');
        }
        try {
            $this->orderService->reject($id);
            $this->addFlash('success', 'Заказ отменен');
            return $this->redirectToRoute('admin_order_view', ['id' => $id]);
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin_order_list');
        }
    }
}