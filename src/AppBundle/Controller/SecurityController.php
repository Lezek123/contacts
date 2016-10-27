<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.10.16
 * Time: 13:57
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Security Controller
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }
        $translator = $this->get('translator');
        $encoder = $this->get('security.password_encoder');
        $user = new User();
        $rep = $this->get('app.user_rep');
        $form = $this->createForm('AppBundle\Form\UserType', $user, array('validation_groups' => array('register')));
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $encodedPass = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPass);
            if ($rep->save($user)) {
                $this->addFlash('success', $translator->trans('register.success'));

                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('error', $translator->trans('register.failed'));
            }
        }

        return $this->render('security/register.html.twig', array('form' => $form->createView()));
    }
    /**
     * @Route("/toggle_debug", name="toggle_debug")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleDebugAction(Request $request)
    {
        $this->get('session')->set('debug_mode', $this->get('session')->get('debug_mode') ? false : true);
        $referer = $request->headers->get('referer');
        if ($referer) {
            return new RedirectResponse($referer);
        } else {
            return $this->redirectToRoute('home');
        }
    }
}
