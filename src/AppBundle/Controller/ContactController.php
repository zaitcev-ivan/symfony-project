<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact_index_page")
     */
    public function indexAction()
    {
        return $this->render('contact/index.html.twig', [

        ]);
    }
}