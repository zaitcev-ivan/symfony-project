<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactCreateType;
use AppBundle\Service\ContactService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    private $contactService;

    public function __construct
    (
        ContactService $contactService
    )
    {
        $this->contactService = $contactService;
    }

    /**
     * @Route("/contact", name="contact_index_page")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function indexAction(Request $request)
    {
        $contactForm = $this->createForm(ContactCreateType::class);

        $contactForm->handleRequest($request);

        if($contactForm->isValid()) {
            try {
                $this->contactService->create($contactForm->getData());
                $this->addFlash('success', 'Ваше сообщение успшено отправлено!');
            } catch(\DomainException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
            return $this->redirectToRoute('contact_index_page');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $contactForm->createView(),
        ]);
    }
}