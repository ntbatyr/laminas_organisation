<?php
declare(strict_types=1);

namespace Organisation\Controller;

use Laminas\Db\Adapter\Adapter;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Organisation\Form\AppointForm;
use Organisation\Form\DepartmentForm;
use Organisation\Model\DepartmentEmployeesTable;
use Organisation\Model\DepartmentsTable;

class DepartmentController extends AbstractActionController
{
    private Adapter $adapter;
    private DepartmentsTable $table;
    private DepartmentEmployeesTable $departmentEmployeesTable;

    public function __construct(Adapter $adapter, DepartmentsTable $table, DepartmentEmployeesTable $departmentEmployeesTable)
    {
        $this->adapter = $adapter;
        $this->table = $table;
        $this->departmentEmployeesTable = $departmentEmployeesTable;
    }

    public function indexAction(): ViewModel
    {
        $departments = $this->table->all();

        return new ViewModel(['departments' => $departments]);
    }

    public function formAction(): ViewModel
    {
        $matches = $this->getEvent()->getRouteMatch();
        $id = (int) $matches->getParam('id', null);
        $department = null;
        $viewArguments = [];

        if (!empty($id) && $id > 0) {
            $department = $this->table->getById($id);
            $employees = $this->departmentEmployeesTable->getDepartmentEmployees($department->id); // сотрудники отдела
            $outOfDepartment = $this->departmentEmployeesTable->employeesOutOfDepartment($department->id); // сотрудники не из отдела

            $appointFrom = new AppointForm($department, $outOfDepartment);

            $viewArguments['department'] = $department;
            $viewArguments['employees'] = $employees;
            $viewArguments['appointForm'] = $appointFrom;
        }

        $form = new DepartmentForm($department);
        $viewArguments['form'] = $form;

        return new ViewModel($viewArguments);
    }

    public function saveAction(): Response
    {
        $request = $this->getRequest();
        $form = new DepartmentForm();
        $urlBack = '/departments/form';

        if ($request->isPost()) {
            try {
                $formData = $request->getPost()->toArray();
                $form->setInputFilter($this->table->getDepartmentFromFilter());
                $form->setData($formData);

                if ($form->isValid()) {
                    $data = $form->getData();
                    if (isset($data['department']) && $data['department'] > 0)
                        $urlBack .= "/{$data['department']}";

                    $this->table->save($data);
                    $this->flashMessenger()->addSuccessMessage('successfully_saved');
                }
            } catch (\Exception $exception) {
                $this->flashMesenger()->addErrorMessage('not_saved');
            }
        }

        return $this->redirect()->toUrl($urlBack);
    }

    public function dismissAction(): Response
    {
        $request = $this->getRequest();
        $data = $request->getPost()->toArray();
        try {
            $urlBack = "/departments/form/{$data['department']}";
            $this->departmentEmployeesTable->remove((int) $data['department'], (int) $data['employee']);

            $this->flashMessenger()->addSuccessMessage('employee_removed_from_department');
        } catch (\Exception $exception) {
            $this->flashMesenger()->addErrorMessage($exception->getMessage());
        }

        return $this->redirect()->toUrl($urlBack);
    }

    public function appointAction(): Response
    {
        $request = $this->getRequest();
        $data = $request->getPost()->toArray();
        $urlBack = "/departments/form/{$data['department']}";

        try {
            foreach ($data['employees'] as $employee) {
                $this->departmentEmployeesTable->create((int) $data['department'], (int) $employee);
            }
            $this->flashMessenger()->addSuccessMessage('successfully_saved');
        } catch (\Exception $exception) {
            $this->flashMesenger()->addErrorMessage($exception->getMessage());
        }

        return $this->redirect()->toUrl($urlBack);
    }
}