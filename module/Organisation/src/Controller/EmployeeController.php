<?php
declare(strict_types = 1);

namespace Organisation\Controller;

use Laminas\Db\Adapter\Adapter;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Organisation\Form\EmployeeForm;
use Organisation\Model\EmployeeTable;

class EmployeeController extends AbstractActionController
{
    private Adapter $adapter;
    private EmployeeTable $table;

    public function __construct(Adapter $adapter, EmployeeTable $table)
    {
        $this->adapter = $adapter;
        $this->table = $table;
    }
    public function indexAction(): ViewModel
    {
        $employees = $this->table->all();

        return new ViewModel(['employees' => $employees]);
    }

    public function formAction(): ViewModel
    {
        $matches = $this->getEvent()->getRouteMatch();
        $id = (int) $matches->getParam('id', null);

        $employee = null;

        if (!empty($id))
            $employee = $this->table->getById($id);

        $form = new EmployeeForm($employee);

        return new ViewModel(['form' => $form]);
    }

    public function saveAction(): Response
    {
        $urlBack = '/employees/form';
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form = new EmployeeForm();
            try {
                $formData = $request->getPost()->toArray();
                $form->setInputFilter($this->table->getEmployeeFromFilter());
                $form->setData($formData);

                if ($form->isValid()) {
                    $data = $form->getData();
                    if (isset($data['employee']) && (int) $data['employee'] > 0)
                        $urlBack .= "/{$data['employee']}";

                    $this->table->save($data);
                    $this->flashMessenger()->addSuccessMessage('successfully_saved');
                }
            } catch (\Exception $exception) {
                $this->flashMesenger()->addErrorMessage('not_saved');
            }
        }

        return $this->redirect()->toUrl($urlBack);
    }

    public function removeAction(): Response
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();

            try {
                if (empty((int) $post['employee']))
                    throw new \Exception('cant_find_employee');

                $employee = $this->table->getById((int) $post['employee']);

                if (empty($employee))
                    throw new \Exception('employee_not_exists');

                $this->table->remove($employee->id);
                $this->flashMessenger()->addSuccessMessage('successfully_deleted');
            } catch (\Exception $exception) {
                $this->flashMesenger()->addErrorMessage($exception->getMessage());
            }
        }

        return $this->redirect()->toRoute('employees');
    }
}