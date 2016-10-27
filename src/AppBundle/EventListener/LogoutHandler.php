<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

/**
 * Class LogoutHandler
 */
class LogoutHandler implements LogoutSuccessHandlerInterface
{
    /**
     * Removes debug_mode from session on logout
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        $referer = $request->headers->get('referer');
        $request->getSession()->remove('debug_mode');

        return new RedirectResponse($referer);
    }
}
