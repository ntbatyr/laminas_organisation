<?php
declare(strict_types = 1);

namespace Auth\Controller;

use Laminas\Authentication\AuthenticationService;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class LogoutController extends AbstractActionController
{
    public function indexAction(): Response|ViewModel
    {
        $authService = new AuthenticationService();

        if ($authService->hasIdentity()) {
            $authService->clearIdentity();
        }

        return $this->redirect()->toRoute('login');
    }
}