<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Dto\DeliveryMethodDto;
use AppBundle\Entity\DeliveryMethod;
use AppBundle\Form\DeliveryMethodType;
use AppBundle\Service\DeliveryMethodService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DeliveryMethodController
 * @package AppBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class DeliveryMethodController extends Controller
{
    private $deliveryMethodService;

    public function __construct(DeliveryMethodService $deliveryMethodService)
    {
        $this->deliveryMethodService = $deliveryMethodService;
    }

    /**
     * @Route("/admin/delivery/list", name="admin_delivery_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $deliveryMethodList = $em->getRepository('AppBundle:DeliveryMethod')->findAll();

        return $this->render('admin/delivery/list.html.twig', [
            'deliveryMethodList' => $deliveryMethodList,
        ]);
    }

    /**
     * @Route("/admin/delivery/create", name="admin_delivery_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $createForm = $this->createForm(DeliveryMethodType::class);
        $createForm->handleRequest($request);

        if($createForm->isValid()) {
            $this->deliveryMethodService->create($createForm->getData());
            $this->addFlash('success', 'Метод доставки создан');
            return $this->redirectToRoute('admin_delivery_list');
        }

        return $this->render('admin/delivery/create.html.twig', [
            'createForm' => $createForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/delivery/edit/{id}", requirements={"id": "\d+"}, name="admin_delivery_edit")
     * @param Request $request
     * @param DeliveryMethod $deliveryMethod
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, DeliveryMethod $deliveryMethod)
    {
        $editForm = $this->createForm(DeliveryMethodType::class, new DeliveryMethodDto($deliveryMethod));

        $editForm->handleRequest($request);

        if($editForm->isValid()) {
            $this->deliveryMethodService->edit($deliveryMethod->getId(), $editForm->getData());
            $this->addFlash('success', 'Метод доставки изменен');
            return $this->redirectToRoute('admin_delivery_list');
        }

        return $this->render('admin/delivery/edit.html.twig', [
            'editForm' => $editForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/delivery/delete/{id}", requirements={"id": "\d+"}, name="admin_delivery_delete")
     * @param Request $request
     * @param DeliveryMethod $deliveryMethod
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, DeliveryMethod $deliveryMethod)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_delivery_list');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($deliveryMethod);
        $em->flush();

        $this->addFlash('success', 'Метод доставки удален');

        return $this->redirectToRoute('admin_delivery_list');
    }
}