<?php


namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\LoginForm;
use AppBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request)
    {
        $loginForm = $this->createForm(LoginForm::class);

        $registrationForm = $this->createForm(UserRegistrationForm::class);

        $registrationForm->handleRequest($request);

        if($registrationForm->isValid()) {

            /** @var User $user */
            $user = $registrationForm->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Welcome, ' . $user->getEmail());

            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );
        }

        $error = null;

        return $this->render('login/index.html.twig', [
            'loginForm' => $loginForm->createView(),
            'registrationForm' => $registrationForm->createView(),
            'error' => $error,
        ]);
    }
}