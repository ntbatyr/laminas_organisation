<?php
declare(strict_types=1);

namespace Organisation\Model;

use Application\Model\Abstracts\AbstractApplicationTableGateway;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\ResultInterface;

class DepartmentEmployeesTable extends AbstractApplicationTableGateway
{
    protected $adapter;
    protected $table = 'department_employees';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function create(int $departmentId, int $employeeId): ResultInterface
    {
        if ($this->existsDepartmentEmployee($departmentId, $employeeId))
            throw new \Exception('employee_already_in_department');

        $values = [
            'department_id' => $departmentId,
            'employee_id' => $employeeId
        ];

        $query = $this->sql->insert()->values($values);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        return $stmt->execute();
    }

    public function remove(int $departmentId, int $employeeId): ResultInterface
    {
        $query = $this->sql->delete()->where(['department_id' => $departmentId])->where(['employee_id' => $employeeId]);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        return $stmt->execute();
    }

    /**
     * @param int $employeeId
     * @return array|Department[]
     */
    public function getEmployeeDepartments(int $employeeId): array
    {
        $set = $this->adapter->query("select id, last_name, first_name, middle_name, birth_date, birth_place  from departments where id in (select department_id from {$this->table} where employee_id = {$employeeId})")->execute();

        return $this->collectionFromSet($set, Department::class);
    }

    /**
     * @param int $departmentId
     * @return array|Employee[]
     */
    public function getDepartmentEmployees(int $departmentId): array
    {
        $set = $this->adapter->query("select id, last_name, first_name, middle_name, birth_date, birth_place  from employees where id in (select employee_id from {$this->table} where department_id = {$departmentId})")->execute();

        return $this->collectionFromSet($set, Employee::class);
    }

    /**
     * @param int $departmentId
     * @return array|Department[]
     */
    public function employeesOutOfDepartment(int $departmentId): array
    {
        $set = $this->adapter->query("select id, last_name, first_name, middle_name, birth_date, birth_place  from employees where id not in (select employee_id from {$this->table} where department_id = {$departmentId})")->execute();

        return $this->collectionFromSet($set, Employee::class);
    }

    private function existsDepartmentEmployee(int $departmentId, int $employeeId): bool
    {
        $query = $this->sql->select()->where(['department_id' => $departmentId])->where(['employee_id' => $employeeId]);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        $rel = $stmt->execute()->current();

        return !empty($rel);
    }
}