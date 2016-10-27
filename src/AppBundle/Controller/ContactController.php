<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method as Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Services\PaginationManager;
use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Contact controller.
 *
 * @Route("/contact")
 */
class ContactController extends Controller
{

    /**
     * Lists all Contact entities.
     *
     * @Route("/{page}", name="contact_index", requirements={"page": "\d+"}, defaults={"page":"1"})
     * @Method("GET")
     * @param Request $request
     * @param integer $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page)
    {
        /*
        $rep = $this->get('app.contact_rep');
        if ($request->getSession()->get('debug_mode')) {
            $pm = new PaginationManager($rep->getPaginationQB(true), $page, 10);
        } else {
            $pm = new PaginationManager($rep->getPaginationQB(), $page, 10);
        }

        return $this->render('contact/index.html.twig', array(
            'contacts' => $pm->getRecords(),
            'maxPages' => $pm->getMaxPages(),
            'page' => $pm->getPage(),
        ));
        */
    }

    /**
     * Creates a new Contact entity.
     *
     * @Route("/new", name="contact_new")
     * @Method({"GET", "POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $rep = $this->get('app.contact_rep');
        $translator = $this->get('translator');
        $contact = new Contact();
        $form = $this->createForm('AppBundle\Form\ContactType', $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($rep->save($contact)) {
                $this->addFlash(
                    'success',
                    $translator->trans('%contact_name%.added', array('%contact_name%' => $contact->getFullName()))
                );
            } else {
                $this->addFlash('error', $translator->trans('contact.delete.failed'));
            }

            return $this->redirectToRoute('contact_show', array('id' => $contact->getId()));
        }

        return $this->render('contact/form.html.twig', array(
            'contact' => $contact,
            'form' => $form->createView(),
            'action' => 'create',
            'startIndexing' => 1,
        ));
    }

    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/show/{id}", name="contact_show")
     * @Method("GET")
     * @param Request                   $request
     * @param \AppBundle\Entity\Contact $contact
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, Contact $contact)
    {
        $debugMode = $request->getSession()->get('debug_mode');
        if ($contact->getIsDeleted() and !$debugMode) {
            throw $this->createNotFoundException('This contact was deleted');
        }
        $deleteForm = $this->createForm('AppBundle\Form\ContactType', $contact, array(
            'validation_groups' => $debugMode ? array('delete', 'debug') : array('delete'),
            'action' => $this->generateUrl('contact_delete', array('id' => $contact->getId())),
        ));

        return $this->render('contact/show.html.twig', array(
            'contact' => $contact,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="contact_edit")
     * @Method({"GET", "POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\Contact                 $contact
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Contact $contact)
    {
        $rep = $this->get('app.contact_rep');
        $translator = $this->get('translator');
        $groups = $request->getSession()->get('debug_mode') ? array('Default', 'debug') : array('Default');
        $editForm = $this->createForm('AppBundle\Form\ContactType', $contact, array(
            'validation_groups' => $groups,
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($rep->save($contact)) {
                $this->addFlash('success', $translator->trans('changes.saved'));
            } else {
                $this->addFlash('error', $translator->trans('contact.save.failed'));
            }

            return $this->redirectToRoute('contact_edit', array('id' => $contact->getId()));
        }

        $lastDetailId = $contact->getDetails()->last() ? $contact->getDetails()->last()->getId() : 1;

        return $this->render(
            'contact/form.html.twig',
            array(
                'contact' => $contact,
                'form' => $editForm->createView(),
                'action' => 'edit',
                'startIndexing' => $lastDetailId + 1,
            )
        );
    }
    /**
     * Deletes a Contact entity.
     *
     * @Route("/delete/{id}", name="contact_delete")
     * @Method("POST")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\Contact                 $contact
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Contact $contact)
    {
        $debugMode = $request->getSession()->get('debug_mode');
        $translator = $this->get('translator');
        $rep = $this->get('app.contact_rep');
        $form = $this->createForm('AppBundle\Form\ContactType', $contact, array(
            'validation_groups' => $debugMode ? array('delete', 'debug') : array('delete'),
            'action' => $this->generateUrl('contact_delete', array('id' => $contact->getId())),
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $permanent = $debugMode ? $form->get('permanentDelete')->getData() : false;
            if ($rep->delete($contact, $permanent)) {
                $this->addFlash(
                    'success',
                    $translator->trans('%contact_name%.deleted', array('%contact_name%' => $contact->getFullName()))
                );
            } else {
                $this->addFlash('error', $translator->trans('contact.delete.failed'));
            }
        }

        return $this->redirectToRoute('contact_index');
    }
}
