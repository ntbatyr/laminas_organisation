<?php
declare(strict_types=1);
namespace Application\Listener;

use Application\Service\LocaleService;
use Interop\Container\Containerinterface;
use Laminas\Session\Container as SessionContainer;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;

class LocaleListener extends AbstractListenerAggregate
{

    private Containerinterface $container;
    private SessionContainer $sessionContainer;

    public function __construct(Containerinterface $container, $sessionContainer)
    {
        $this->container = $container;
        $this->sessionContainer = $sessionContainer;
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
        $locale = null;

        if ($this->sessionContainer->offsetExists(LocaleService::LOCALE_STORAGE_KEY))
            $locale = $this->sessionContainer->offsetGet(LocaleService::LOCALE_STORAGE_KEY);

        LocaleService::setLocale($locale);
    }
}