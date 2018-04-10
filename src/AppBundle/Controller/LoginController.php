<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /**
     * @Route("/login-index", name="login_index_page")
     */
    public function indexAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $loginForm = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername,
        ]);


        return $this->render('login/index.html.twig', [
            'loginForm' => $loginForm->createView(),
            'error'         => $error,
        ]);
    }

    /**
     * @Route("logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('this should not be reached');
    }
}