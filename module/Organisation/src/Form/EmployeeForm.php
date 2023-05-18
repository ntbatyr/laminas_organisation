<?php
declare(strict_types = 1);

namespace Organisation\Form;

use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\DateSelect;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Organisation\Model\Employee;

class EmployeeForm extends Form
{
    public function __construct(Employee $employee = null, $name = null, array $options = [])
    {
        parent::__construct($name, $options);
        $this->setAttribute('method', 'post');

        $this->add([
            'type' => Hidden::class,
            'name' => 'employee',
            'attributes' => [
                'value' => $employee? $employee->id : 0
            ]
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'last_name',
            'options' => [
                'label' => 'employee_last_name'
            ],
            'attributes' => [
                'required' => true,
                'size' => 100,
                'maxlength' => 100,
                'pattern' => '^[а-яА-ЯёЁжЖйЙa-zA-Z0-9\s-]+$',
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'title' => 'employee_last_name',
                'placeholder' => 'employee_last_name_placeholder',
                'value' => $employee ? $employee->last_name : ''
            ]
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'first_name',
            'options' => [
                'label' => 'employee_first_name'
            ],
            'attributes' => [
                'required' => true,
                'size' => 100,
                'maxlength' => 100,
                'pattern' => '^[а-яА-ЯёЁжЖйЙa-zA-Z0-9\s-]+$',
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'title' => 'employee_first_name',
                'placeholder' => 'employee_first_name_placeholder',
                'value' => $employee ? $employee->first_name : ''
            ]
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'middle_name',
            'options' => [
                'label' => 'employee_middle_name'
            ],
            'attributes' => [
                'required' => false,
                'size' => 100,
                'maxlength' => 100,
                'pattern' => '^[а-яА-ЯёЁжЖйЙa-zA-Z0-9\s-]+$',
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'title' => 'employee_middle_name',
                'placeholder' => 'employee_middle_name_placeholder',
                'value' => $employee ? $employee->middle_name : ''
            ]
        ]);

        $birthDate = null;
        if (!empty($employee))
            $birthDate = date_create_from_format('Y-m-d', $employee->birth_date);

        $this->add([
            'type' => DateSelect::class,
            'name' => 'birth_date',
            'options' => [
                'label' => 'select_employee_birth_date',
                'create_empty_option' => true,
                'max_year' => date('Y') - 16, // старше 16 лет
                'min_year' => date('Y') - 65, // старше 16 лет
                'year_attributes' => [
                    'class' => 'custom-select w-300',
                    'value' => $birthDate ? $birthDate->format('Y') : '',
                ],
                'month_attributes' => [
                    'class' => 'custom-select w-300',
                    'value' => $birthDate ? $birthDate->format('m') : '',
                ],
                'day_attributes' => [
                    'class' => 'custom-select w-300',
                    'value' => $birthDate ? $birthDate->format('d') : '',
                    'id' => 'day'
                ]
            ],
            'attributes' => [
                'required' => true,
            ]
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'birth_place',
            'options' => [
                'label' => 'employee_birth_place'
            ],
            'attributes' => [
                'required' => true,
                'size' => 100,
                'maxlength' => 100,
                'pattern' => '^[а-яА-ЯёЁжЖйЙa-zA-Z0-9\s(),.-]+$',
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'title' => 'employee_birth_place',
                'placeholder' => 'employee_birth_place_placeholder',
                'value' => $employee ? $employee->birth_place : ''
            ]
        ]);

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
            'name' => 'save',
            'attributes' => [
                'value' => 'save',
                'class' => 'btn btn-primary'
            ]
        ]);
    }
}