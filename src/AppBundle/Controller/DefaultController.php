<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categoryRepository = $em->getRepository('AppBundle:Category');
        $categories = $categoryRepository->childrenHierarchy();

        $brandList = $em->getRepository('AppBundle:Brand')->findAll();

        return $this->render('default/index.html.twig', [
            'categories' => $categories,
            'brands' => $brandList,
        ]);
    }
}
