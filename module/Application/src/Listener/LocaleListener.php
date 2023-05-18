<?php

namespace Application\Listener;

use Interop\Container\Containerinterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Locale;

class LocaleListener extends \Laminas\EventManager\AbstractListenerAggregate
{
    private const REGEX_LOCALE = '#^(?P<locale>[a-z]{2,3}([-_][a-zA-Z]{2}|))#';
    private const DEFAULT_LOCALE = 'ru_RU';

    private Containerinterface $container;

    public function __construct(Containerinterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH,
            [
                $this,
                'setLocale'
            ]
        );
    }

    public function setLocale(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();

        if (!$routeMatch) {
            $this->setDefaultLocale();
            return;
        }

        $locale = $routeMatch->getParam('locale');
        if (!$locale || !preg_match(self::REGEX_LOCALE, $locale, $matches)) {
            $this->setDefaultLocale();
            return;
        }

        Locale::setDefault(Locale::canonicalize($matches['locale']));
    }

    private function setDefaultLocale() : void
    {
        Locale::setDefault(self::DEFAULT_LOCALE);
    }
}