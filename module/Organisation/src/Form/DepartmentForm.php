<?php
declare(strict_types=1);

namespace Organisation\Form;

use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Organisation\Model\Department;

class DepartmentForm extends Form
{
    public function __construct(Department $department = null, $name = null, array $options = [])
    {
        parent::__construct('department', $options);
        $this->setAttribute('method', 'post');
        $this->add([
            'type' => Hidden::class,
            'name' => 'department',
            'attributes' => [
                'value' => $department? $department->id : 0
            ]
        ]);


        $this->add([
            'type' => Text::class,
            'name' => 'name',
            'options' => [
                'label' => 'department_name'
            ],
            'attributes' => [
                'required' => true,
                'size' => 100,
                'maxlength' => 100,
                'pattern' => '^[a-zA-Z0-9\s]+$',
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'title' => 'alpha_numeric_characters_only',
                'placeholder' => 'department_name_placeholder',
                'value' => $department ? $department->name : ''
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