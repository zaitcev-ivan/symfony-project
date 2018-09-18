<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatalogController extends Controller
{
    /**
     * @Route("/catalog", name="catalog_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categoryRepository = $em->getRepository('AppBundle:Category');
        $categories = $categoryRepository->childrenHierarchy();

        $brandList = $em->getRepository('AppBundle:Brand')->findAll();

        $productRepository = $em->getRepository('AppBundle:Product');

        $products = $productRepository->findLastProducts();

        return $this->render('catalog/index.html.twig', [
            'categories' => $categories,
            'brands' => $brandList,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/catalog/category/{categoryId}", requirements={"categoryId": "\d+"}, name="catalog_category")
     * @param $categoryId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($categoryId)
    {
        $em = $this->getDoctrine()->getManager();

        $categoryRepository = $em->getRepository('AppBundle:Category');
        $categories = $categoryRepository->childrenHierarchy();

        $brandList = $em->getRepository('AppBundle:Brand')->findAll();

        $productRepository = $em->getRepository('AppBundle:Product');

        $products = $productRepository->findAllByCategory($categoryId);
        $category = $categoryRepository->get($categoryId);
        $categoryName = $category->getName();

        return $this->render('catalog/category.html.twig', [
            'categories' => $categories,
            'brands' => $brandList,
            'products' => $products,
            'categoryName' => $categoryName,
        ]);
    }
}