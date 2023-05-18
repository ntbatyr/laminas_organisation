<?php
declare(strict_types=1);

namespace Organisation\Form;

use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Submit;
use Organisation\Model\Department;
use Organisation\Model\Employee;

class AppointForm extends \Laminas\Form\Form
{
    /**
     * @param array|Employee[] $employees
     * @param $name
     * @param array $options
     */
    public function __construct(Department $department, array $employees, $name = null, array $options = [])
    {
        parent::__construct($name, $options);
        $this->setAttribute('method', 'post');

        $this->add([
            'type' => Hidden::class,
            'name' => 'department',
            'attributes' => [
                'value' =>$department->id
            ]
        ]);

        $i = 0;
        foreach ($employees as $employee) {
            $this->add([
                'type' => Checkbox::class,
                'name' => "employees[{$i}]",
                'options' => [
                    'label' => rtrim("{$employee->last_name} {$employee->first_name} {$employee->middle_name}"),
                    'label_attributes' => [
                        'for' => "employee{$employee->id}",
                        'class' => 'form-check-label'
                    ],
                    'use_hidden_element' => false,
                    'checked_value' => (string)$employee->id,
                    'unchecked_value' => null
                ],
                'attributes' => [
                    'class' => 'form-check-input',
                    'value' => (string)$employee->id,
                    'id' => "employee{$employee->id}"
                ]
            ]);

            $i++;
        }

        $this->add([
            'type' => Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ]
        ]);

        $this->add([
            'type' => Submit::class,
            'name' => 'save_department',
            'attributes' => [
                'value' => 'add',
                'class' => 'btn btn-primary'
            ]
        ]);
    }
}