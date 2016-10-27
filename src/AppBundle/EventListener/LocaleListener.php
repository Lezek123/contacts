<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class LocaleListener
 */
class LocaleListener implements EventSubscriberInterface
{
    private $defaultLocale;
    private $locales;
    private $urlGenerator;

    /**
     * LocaleListener constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param array                 $locales
     * @param string                $defaultLocale
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, $locales, $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
        $this->locales = explode('|', trim($locales));
        $this->urlGenerator = $urlGenerator;
        array_unshift($this->locales, $this->defaultLocale);
        $this->locales = array_unique($this->locales);
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        /* Get locale from route and set to session */
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        /* If no locale in route - get from session */
        } elseif ($request->getSession()->get('_locale')) {
            $locale = $request->getSession()->get('_locale', $this->defaultLocale);
        /* If no locale set in session too - get preferred language */
        } else {
            $locale = $request->getPreferredLanguage($this->locales);
        }
        /* If homepage - redirect to locale */
        if ($request->getPathInfo() === '/') {
            $response = new RedirectResponse($this->urlGenerator->generate('home', ['_locale' => $locale]));
            $event->setResponse($response);
        }
        /* Make sure the locale is right when, for example, on error page */
        $request->setLocale($locale);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }
}
