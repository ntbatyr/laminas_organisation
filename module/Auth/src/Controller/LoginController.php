<?php
declare(strict_types = 1);

namespace Auth\Controller;
use Auth\Form\LoginForm;
use Auth\Model\UsersTable;
use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result;
use Laminas\Db\Adapter\Adapter;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class LoginController extends AbstractActionController
{
    private Adapter $adapter;
    private UsersTable $usersTable;
    public function __construct(Adapter $adapter, UsersTable $usersTable)
    {
        $this->adapter = $adapter;
        $this->usersTable = $usersTable;
        $this->createDummyUser(); // Создаем пользователя, не лучший способ конечно ...
    }

    public function indexAction(): Response|ViewModel
    {
        $authService = new AuthenticationService();
        if ($authService->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new LoginForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($this->usersTable->getLoginFromFilter());
            $form->setData($data);

            if ($form->isValid()) {
                $authAdapter = new CredentialTreatmentAdapter($this->adapter);
                $authAdapter->setTableName($this->usersTable->getTable())
                    ->setIdentityColumn('login')
                    ->setCredentialColumn('password')
                    ->getDbSelect();

                $credentials = $form->getData();
                $authAdapter->setIdentity($credentials['login']);

                $user = $this->usersTable->findByLogin($credentials['login']);

                if (password_verify($credentials['password'], $user->password))
                    $authAdapter->setCredential($user->password);
                else
                    $authAdapter->setCredential('');

                $result = $authService->authenticate($authAdapter);
                $code = $result->getCode();

                if ($code === Result::SUCCESS) {
                    $storage = $authService->getStorage();
                    $storage->write($authAdapter->getResultRowObject());

                    return $this->redirect()->toRoute('departments');
                }

                match ($code) {
                    Result::FAILURE_IDENTITY_NOT_FOUND => $this->flashMessanger()->addErrorMessage('login_not_found'),
                    Result::FAILURE_CREDENTIAL_INVALID => $this->flashMessanger()->addErrorMessage('wrong_password'),
                    default => $this->flashMessenger()->addErrorMessage('auth_failed'),
                };

                return $this->redirect()->refresh();
            }
        }

        return new ViewModel(['form' => $form]);
    }

    private function createDummyUser(): void
    {
        try {
            $dummyUser = $this->usersTable->findByLogin('admin');
            if (!empty($dummyUser))
                return;

            $this->usersTable->create([
                'login' => 'admin',
                'password' => 'admin123'
            ]);
        } catch (\RuntimeException $exception) {
            dd($exception->getMessage(), $exception->getTrace());
        }
    }

}