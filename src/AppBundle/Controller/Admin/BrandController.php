<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Dto\BrandDto;
use AppBundle\Entity\Brand;
use AppBundle\Form\BrandType;
use AppBundle\Service\BrandService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class BrandController
 * @package AppBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class BrandController extends Controller
{
    private $brandService;

    public function __construct
    (
        BrandService $brandService
    )
    {

        $this->brandService = $brandService;
    }

    /**
     * @Route("/admin/brand/list", name="admin_brand_list")
     * @throws \LogicException
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $brandList = $em->getRepository('AppBundle:Brand')->findAll();

        return $this->render('admin/brand/list.html.twig', [
            'brandList' => $brandList,
        ]);
    }

    /**
     * @Route("/admin/brand/create", name="admin_brand_create")
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function createAction(Request $request): Response
    {
        $createForm = $this->createForm(BrandType::class);

        $createForm->handleRequest($request);

        if($createForm->isValid()) {
            $this->brandService->create($createForm->getData());
            $this->addFlash('success', 'Бренд создан');
            return $this->redirectToRoute('admin_brand_list');
        }

        return $this->render('admin/brand/create.html.twig', [
            'createForm' => $createForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/brand/edit/{id}", requirements={"id": "\d+"}, name="admin_brand_edit")
     * @param Request $request
     * @param Brand $brand
     * @return Response
     * @throws \LogicException
     */
    public function editAction(Request $request, Brand $brand): Response
    {
        $editForm = $this->createForm(BrandType::class, new BrandDto($brand));

        $editForm->handleRequest($request);

        if($editForm->isValid()) {
            $this->brandService->edit($brand->getId(), $editForm->getData());
            $this->addFlash('success', 'Бренд изменен');
            return $this->redirectToRoute('admin_brand_list');
        }

        return $this->render('admin/brand/edit.html.twig', [
            'editForm' => $editForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/brand/delete/{id}", requirements={"id": "\d+"}, name="admin_brand_delete")
     * @Method("POST")
     * @param Brand $brand
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \LogicException
     */
    public function deleteAction(Request $request, Brand $brand): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_contact_list');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($brand);
        $em->flush();

        $this->addFlash('success', 'Бренд удален');

        return $this->redirectToRoute('admin_brand_list');

    }
}