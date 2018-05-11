<?php

namespace AppBundle\Controller;

use AppBundle\Form\PasswordResetRequestType;
use AppBundle\Form\PasswordResetType;
use AppBundle\Service\PasswordResetService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Zend\EventManager\Exception\DomainException;

/**
 * Class ResetController
 * @package AppBundle\Controller
 *
 */
class ResetController extends Controller
{

    private $passwordResetService;

    public function __construct
    (
        PasswordResetService $passwordResetService
    )
    {
        $this->passwordResetService = $passwordResetService;
    }

    /**
     * @Route("reset/request", name="password_reset_request")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException
     * @throws \Exception
     */
    public function requestAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $passwordResetForm = $this->createForm(PasswordResetRequestType::class);

        $passwordResetForm->handleRequest($request);

        if ($passwordResetForm->isValid()) {
            try {
                $this->passwordResetService->request($passwordResetForm->getData());
                $this->addFlash('success', 'Проверьте почту');
                return $this->redirectToRoute('login_index_page');
            }catch(\DomainException $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('password_reset_request');
            }
        }

        $errors = $passwordResetForm->getErrors();

        return $this->render('reset/request.html.twig', [
            'passwordResetForm' => $passwordResetForm->createView(),
            'errors' => $errors,
        ]);
    }

    /**
     * @Route("reset/confirm/{token}", name="password_reset_confirm")
     * @param Request $request
     * @param $token
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \DomainException
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function confirmAction(Request $request, $token)
    {
        try {
            $this->passwordResetService->validateToken($token);
        } catch(\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $passwordResetForm = $this->createForm(PasswordResetType::class);

        $passwordResetForm->handleRequest($request);

        if($passwordResetForm->isValid()) {
            try {
                $this->passwordResetService->resetPassword($token, $passwordResetForm->getData());
                $this->addFlash('success', 'Новый пароль сохранен');
                return $this->redirectToRoute('login_index_page');
            } catch(DomainException $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('password_reset_confirm');
            }
        }

        $errors = $passwordResetForm->getErrors();

        return $this->render('reset/confirm.html.twig', [
            'passwordResetForm' => $passwordResetForm->createView(),
            'errors' => $errors,
        ]);

    }
}