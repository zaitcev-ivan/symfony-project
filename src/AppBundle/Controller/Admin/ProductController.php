<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Dto\ProductDto;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use AppBundle\Service\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package AppBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class ProductController extends Controller
{
    private $productService;

    public function __construct
    (
        ProductService $productService
    )
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/admin/product/list", name="admin_product_list")
     * @throws \LogicException
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productList = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('admin/product/list.html.twig', [
            'productList' => $productList,
        ]);
    }

    /**
     * @Route("/admin/product/create", name="admin_product_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function createAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->categoryList();
        $brands = $em->getRepository('AppBundle:Brand')->findAll();
        $characteristicList = $em->getRepository('AppBundle:Characteristic')->findAll();

        $createForm = $this->createForm(ProductType::class, new ProductDto(null, $characteristicList), [
            'categories' => $categories,
            'brands' => $brands,
            'characteristic' => $characteristicList,
        ]);

        $createForm->handleRequest($request);

        if($createForm->isValid()) {
            try {
                $this->productService->create($createForm->getData());
                $this->addFlash('success', 'Продукт создан');
                return $this->redirectToRoute('admin_product_list');
            } catch(\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('admin_product_create');
            }
        }

        return $this->render('admin/product/create.html.twig', [
            'createForm' => $createForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/edit/{id}", requirements={"id": "\d+"}, name="admin_product_edit")
     * @param Request $request
     * @param Product $product
     * @return Response
     * @throws \LogicException
     */
    public function editAction(Request $request, Product $product): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->categoryList();
        $brands = $em->getRepository('AppBundle:Brand')->findAll();

        $editForm = $this->createForm(ProductType::class, new ProductDto($product), [
            'categories' => $categories,
            'brands' => $brands,
        ]);

        $editForm->handleRequest($request);

        if($editForm->isValid()) {
            try {
                $this->productService->edit($product, $editForm->getData());
                $this->addFlash('success', 'Продукт изменен');
                return $this->redirectToRoute('admin_product_list');
            } catch(\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('admin_product_edit');
            }
        }

        return $this->render('admin/product/edit.html.twig', [
            'editForm' => $editForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/delete/{id}", requirements={"id": "\d+"}, name="admin_product_delete")
     * @param Request $request
     * @param Product $product
     * @return Response
     * @throws \LogicException
     */
    public function deleteAction(Request $request, Product $product): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_product_list');
        }

        try {
            $this->productService->delete($product);
            $this->addFlash('success', 'Продукт удален');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('admin_product_list');
    }
}