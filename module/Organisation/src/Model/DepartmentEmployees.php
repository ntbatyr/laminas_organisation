<?php
declare(strict_types = 1);

namespace Organisation\Model;

class DepartmentEmployees extends \ArrayObject
{
    public int $department_id;
    public int $employee_id;

    /**
     * @param int $department_id
     */
    public function setDepartmentId(int $department_id): void
    {
        $this->department_id = $department_id;
    }

    /**
     * @param int $employee_id
     */
    public function setEmployeeId(int $employee_id): void
    {
        $this->employee_id = $employee_id;
    }
}