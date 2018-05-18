<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Dto\CharacteristicDto;
use AppBundle\Entity\Characteristic;
use AppBundle\Form\CharacteristicType;
use AppBundle\Service\CharacteristicService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CharacteristicController
 * @package AppBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class CharacteristicController extends Controller
{
    private $characteristicService;

    public function __construct
    (
        CharacteristicService $characteristicService
    )
    {
        $this->characteristicService = $characteristicService;
    }

    /**
     * @Route("/admin/characteristic/list", name="admin_characteristic_list")
     * @throws \LogicException
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $characteristicList = $em->getRepository('AppBundle:Characteristic')->findAll();

        return $this->render('admin/characteristic/list.html.twig', [
            'characteristicList' => $characteristicList,
        ]);
    }

    /**
     * @Route("/admin/characteristic/create", name="admin_characteristic_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function createAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $nextMaxSortValue = $em->getRepository('AppBundle:Characteristic')->getMaxSortValue() + 1;

        $createForm = $this->createForm(CharacteristicType::class, new CharacteristicDto(null, $nextMaxSortValue));

        $createForm->handleRequest($request);

        if($createForm->isValid()) {
            try {
                $this->characteristicService->create($createForm->getData());
                $this->addFlash('success', 'Характеристика создана');
                return $this->redirectToRoute('admin_characteristic_list');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('admin_characteristic_create');
            }
        }

        return $this->render('admin/characteristic/create.html.twig', [
            'createForm' => $createForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/characteristic/edit/{id}", requirements={"id": "\d+"}, name="admin_characteristic_edit")
     * @param Request $request
     * @param Characteristic $characteristic
     * @return Response
     * @throws \LogicException
     */
    public function editAction(Request $request, Characteristic $characteristic)
    {

        $editForm = $this->createForm(CharacteristicType::class, new CharacteristicDto($characteristic));

        $editForm->handleRequest($request);

        if($editForm->isValid()) {
            try {
                $this->characteristicService->edit($characteristic, $editForm->getData());
                $this->addFlash('success', 'Характеристика сохранена');
                return $this->redirectToRoute('admin_characteristic_list');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('admin_characteristic_edit');
            }
        }

        return $this->render('admin/characteristic/edit.html.twig', [
            'editForm' => $editForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/characteristic/delete{id}", requirements={"id": "\d+"}, name="admin_characteristic_delete")
     * @param Request $request
     * @param Characteristic $characteristic
     * @return RedirectResponse
     * @throws \LogicException
     */
    public function deleteAction(Request $request, Characteristic $characteristic): RedirectResponse
    {
        if (!$this->validateCsrfToken('delete', $request)) {
            return $this->redirectToRoute('admin_characteristic_list');
        }

        try {
            $this->characteristicService->delete($characteristic);
            $this->addFlash('success', 'Характеристика удалена');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('admin_characteristic_list');
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