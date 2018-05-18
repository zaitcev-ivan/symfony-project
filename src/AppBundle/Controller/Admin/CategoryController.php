<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Dto\CategoryDto;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use AppBundle\Service\CategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 * @package AppBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class CategoryController extends Controller
{
    private $categoryService;

    public function __construct
    (
        CategoryService $categoryService
    )
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/admin/category/list", name="admin_category_list")
     * @throws \LogicException
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categoryList = $em->getRepository('AppBundle:Category')->categoryList();

        return $this->render('admin/category/list.html.twig', [
            'categoryList' => $categoryList,
        ]);
    }

    /**
     * @Route("/admin/category/create", name="admin_category_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function createAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->categoryList();

        $createForm = $this->createForm(CategoryType::class, null, ['categories' => $categories]);

        $createForm->handleRequest($request);
        if ($createForm->isValid()) {
            try {
                $this->categoryService->create($createForm->getData());
                $this->addFlash('success', 'Категория создана');
                return $this->redirectToRoute('admin_category_list');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('admin_category_create');
            }
        }

        return $this->render('admin/category/create.html.twig', [
            'createForm' => $createForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/edit/{id}", requirements={"id": "\d+"}, name="admin_category_edit")
     * @param Request $request
     * @param Category $category
     * @return Response
     * @throws \LogicException
     */
    public function editAction(Request $request, Category $category): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->categoryList([$category->getId()]);

        $editForm = $this->createForm(CategoryType::class, new CategoryDto($category), ['categories' => $categories]);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $this->categoryService->edit($category, $editForm->getData());
                $this->addFlash('success', 'Категория изменена');
                return $this->redirectToRoute('admin_category_list');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('admin_category_edit');
            }
        }

        return $this->render('admin/category/edit.html.twig', [
            'editForm' => $editForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/delete/{id}", requirements={"id": "\d+"}, name="admin_category_delete")
     * @Method("POST")
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function deleteAction(Request $request, Category $category): RedirectResponse
    {
        if (!$this->validateCsrfToken('delete', $request)) {
            return $this->redirectToRoute('admin_category_list');
        }

        try {
            $this->categoryService->delete($category);
            $this->addFlash('success', 'Категория удалена');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('admin_category_list');
    }

    /**
     * @Route("/admin/category/move-up/{id}", requirements={"id": "\d+"}, name="admin_category_move_up")
     * @Method("POST")
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function moveUpAction(Request $request, Category $category): RedirectResponse
    {
        if (!$this->validateCsrfToken('move-up', $request)) {
            return $this->redirectToRoute('admin_category_list');
        }

        try {
            $this->categoryService->moveUp($category);
        } catch(\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('admin_category_list');
    }

    /**
     * @Route("/admin/category/move-down/{id}", requirements={"id": "\d+"}, name="admin_category_move_down")
     * @Method("POST")
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function moveDownAction(Request $request, Category $category): RedirectResponse
    {
        if (!$this->validateCsrfToken('move-down', $request)) {
            return $this->redirectToRoute('admin_category_list');
        }

        try {
            $this->categoryService->moveDown($category);
        } catch(\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('admin_category_list');
    }

    /**
     * @param Request $request
     * @param $csrfName
     * @return bool
     * @throws \LogicException
     */
    private function validateCsrfToken($csrfName, Request $request): bool
    {
        if(!$this->isCsrfTokenValid($csrfName, $request->request->get('token'))) {
            $this->addFlash('error', 'Invalid CSRF token');
            return false;
        }
        return true;
    }
}