<?php

declare(strict_types=1);

namespace Application;

use Application\Service\LocaleService;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function onBootstrap(MvcEvent $event): void
    {
        $application = $event->getApplication();
        $services = $application->getServiceManager();

        $session = new Container(LocaleService::LOCALE_STORAGE_SESSION_ID);
        $locale = null;
        if ($session->offsetExists(LocaleService::LOCALE_STORAGE_KEY))
            $locale = $session->offsetGet(LocaleService::LOCALE_STORAGE_KEY);
        else
            $session->offsetSet(LocaleService::LOCALE_STORAGE_KEY, LocaleService::RU_RU_LOCALE);

        $translator = $services->get(\Laminas\Mvc\I18n\Translator::class);
        $translator->setLocale($locale);
    }
}
