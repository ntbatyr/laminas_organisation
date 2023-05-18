<?php
declare(strict_types = 1);

namespace Auth\Form;

use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct('login', $options);
        $this->setAttribute('method', 'post');

        $this->add([
            'type' => Text::class,
            'name' => 'login',
            'options' => [
                'label' => 'login'
            ],
            'attributes' => [
                'required' => true,
                'size' => 100,
                'maxlength' => 100,
                'pattern' => '^[a-zA-Z0-9]+$',
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'title' => 'login',
            ]
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'options' => [
                'label' => 'password'
            ],
            'attributes' => [
                'required' => true,
                'size' => 100,
                'maxlength' => 100,
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'title' => 'password',
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
            'name' => 'submit_login',
            'attributes' => [
                'value' => 'submit_login',
                'class' => 'btn btn-primary'
            ]
        ]);

    }
}