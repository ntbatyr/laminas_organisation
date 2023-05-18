<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Service\LocaleService;
use Laminas\Http\Header\SetCookie;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function __construct()
    {
        $this->sessionContainer = new Container(LocaleService::LOCALE_STORAGE_SESSION_ID);
    }

    public function indexAction(): ViewModel
    {
        // dd($this->sessionContainer->offsetExists(LocaleService::LOCALE_STORAGE_KEY));

        return new ViewModel();
    }

    public function localeAction(): Response
    {
        $request = $this->getRequest();
        $urlBack = '/';

        if ($request->isPost()) {
            $locale = $request->getPost()->offsetGet('locale') ?? null;
            $urlBack = $request->getPost()->offsetGet('url_back') ?? '/';

            $locale = LocaleService::verifyLocale($locale);
            $this->sessionContainer->offsetSet('user_locale', $locale);
        }

        return $this->redirect()->toUrl($urlBack);
    }
}
