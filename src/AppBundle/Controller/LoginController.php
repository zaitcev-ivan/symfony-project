<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login_index_page")
     */
    public function indexAction()
    {
        return $this->render('login/index.html.twig', [

        ]);
    }
}