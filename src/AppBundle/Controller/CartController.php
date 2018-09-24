<?php

namespace AppBundle\Controller;

use AppBundle\Service\CartService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CartController
 * @package AppBundle\Controller
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class CartController extends Controller
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @Route("/cart", name="cart_index")
     */
    public function indexAction()
    {
        $cart = $this->cartService->getCart();
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/cart/add/{productId}", requirements={"productId": "\d+"}, name="cart_add")
     * @Method({"POST"})
     * @param Request $request
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request, $productId)
    {
        if (!$this->isCsrfTokenValid('addToCart', $request->request->get('token'))) {
            return $this->redirectToRoute('homepage');
        }

        try {
            $this->cartService->add($productId);
            $this->addFlash('success', 'Товар добавлен в корзину');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart/set/{productId}/{quantity}", requirements={"productId": "\d+", "quantity": "\d+"}, name="cart_set")
     * @Method({"POST"})
     * @param Request $request
     * @param $productId
     * @param $quantity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setAction(Request $request, $productId, $quantity)
    {
        if (!$this->isCsrfTokenValid('setToCart', $request->request->get('token'))) {
            return $this->redirectToRoute('homepage');
        }

        try {
            $this->cartService->set($productId, $quantity);
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart/remove/{productId}", requirements={"productId": "\d+"}, name="cart_delete")
     * @Method({"POST"})
     * @param Request $request
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request, $productId)
    {
        if (!$this->isCsrfTokenValid('removeFromCart', $request->request->get('token'))) {
            return $this->redirectToRoute('homepage');
        }

        try {
            $this->cartService->remove($productId);
            $this->addFlash('success', 'Товар удален из корзины');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('cart_index');
    }
}