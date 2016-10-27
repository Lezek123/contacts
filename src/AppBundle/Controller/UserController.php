<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.10.16
 * Time: 09:36
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method as Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Services\PaginationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\User;

/**
 * User Controller
 *
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * Lists all Users
     *
     * @Route("/{page}", name="user_index", requirements={"page": "\d+"}, defaults={"page":"1"})
     * @Method("GET")
     * @param integer $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $rep = $this->get('app.user_rep');
        $pm = new PaginationManager($rep->getPaginationQB(), $page, 1);

        return $this->render('user/index.html.twig', array(
            'users' => $pm->getRecords(),
            'maxPages' => $pm->getMaxPages(),
            'page' => $pm->getPage(),
        ));
    }

    /**
     * Finds and displays a User
     *
     * @Route("/show/{id}", name="user_show")
     * @Method("GET")
     * @param \AppBundle\Entity\User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createForm('AppBundle\Form\UserType', $user, array(
            'validation_groups' => array('delete'),
            'action' => $this->generateUrl('user_delete', array('id' => $user->getId())),
        ));

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\User                    $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $user)
    {
        $rep = $this->get('app.user_rep');
        $translator = $this->get('translator');
        $editForm = $this->createForm('AppBundle\Form\UserType', $user, array('validation_groups' => array('edit')));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($rep->save($user)) {
                $this->addFlash('success', $translator->trans('changes.saved'));
            } else {
                $this->addFlash('error', $translator->trans('user.save.failed'));
            }

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));
    }
    /**
     * Deletes an User
     *
     * @Route("/delete/{id}", name="user_delete")
     * @Method("POST")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\User                    $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, User $user)
    {
        $translator = $this->get('translator');
        $rep = $this->get('app.user_rep');
        $form = $this->createForm('AppBundle\Form\UserType', $user, array(
            'validation_groups' => array('delete'),
            'action' => $this->generateUrl('user_delete', array('id' => $user->getId())),
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $user->getUsername();
            if ($rep->delete($user)) {
                $this->addFlash(
                    'success',
                    $translator->trans('%username%.deleted', array('%username%' => $username))
                );
            } else {
                $this->addFlash('error', $translator->trans('user.delete.failed'));
            }
        } else {
            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->redirectToRoute('user_index');
    }
}
