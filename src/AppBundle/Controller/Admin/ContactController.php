<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ContactController
 * @package AppBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class ContactController extends Controller
{
    /**
     * @Route("/admin/contact/list", name="admin_contact_list")
     * @throws \LogicException
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contactList = $em->getRepository('AppBundle:Contact')->findAll();

        return $this->render('admin/contact/list.html.twig', [
            'contactList' => $contactList,
        ]);
    }

    /**
     * @Route("/admin/contact/show/{id}", requirements={"id": "\d+"}, name="admin_contact_show")
     * @param Contact $contact
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Contact $contact): Response
    {
        return $this->render('admin/contact/show.html.twig', [
            'contact' => $contact
        ]);
    }

    /**
     * @Route("/admin/contact/mark-old/{id}", requirements={"id": "\d+"}, name="admin_contact_mark_old")
     * @Method("POST")
     * @param Request $request
     * @param Contact $contact
     * @return Response
     */
    public function markOldAction(Request $request, Contact $contact): Response
    {
        if (!$this->isCsrfTokenValid('mark_old', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_contact_list');
        }

        $contact->setOldStatus();

        $em = $this->getDoctrine()->getManager();
        $em->persist($contact);
        $em->flush();

        return $this->redirectToRoute('admin_contact_list');
    }

    /**
     * @Route("/admin/contact/delete/{id}", requirements={"id": "\d+"}, name="admin_contact_delete")
     * @Method("POST")
     * @param Request $request
     * @param Contact $contact
     * @return Response
     */
    public function deleteAction(Request $request, Contact $contact): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_contact_list');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();

        return $this->redirectToRoute('admin_contact_list');
    }
}