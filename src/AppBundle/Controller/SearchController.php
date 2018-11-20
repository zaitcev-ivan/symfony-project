<?php

namespace AppBundle\Controller;

use AppBundle\Form\SearchType;
use AppBundle\Service\SearchService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SearchController
 * @package AppBundle\Controller
 */
class SearchController extends Controller
{
    private $searchService;
    
    public function __construct
    (
        SearchService $searchService
    )
    {
        $this->searchService = $searchService;
    }

    /**
     * @Route("/search/index", name="search_index")
     */
    public function indexAction()
    {
        $searchForm = $this->createForm(SearchType::class);
        
        return $this->render('search/_search.html.twig', [
            'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/search/query", name="search_query")
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function searchAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        $products = $this->searchService->search($searchForm->getData());

        $categoryRepository = $em->getRepository('AppBundle:Category');
        $categories = $categoryRepository->childrenHierarchy();

        $brandList = $em->getRepository('AppBundle:Brand')->findAll();
        
        return $this->render('search/query.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brandList,
        ]);
    }
}