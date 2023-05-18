<?php
declare(strict_types=1);

namespace Auth\Model;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Csrf;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class UsersTable extends AbstractTableGateway
{
    protected $adapter;
    protected $table = 'users';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function getLoginFromFilter(): InputFilter
    {
        $inputFilter = new InputFilter();
        $factory = new Factory();

        $inputFilter->add(
            $factory->createInput([
                'name' => 'login',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    ['name' => NotEmpty::class],
                    [
                        'name' => StringLength::class,
                        'option' => [
                            'min' => 3,
                            'max' => 100,
                            'messages' => [
                                StringLength::TOO_SHORT => 'string_is_too_short',
                                StringLength::TOO_LONG => 'string_is_too_long',
                            ]
                        ]
                    ]
                ]
            ])
        );

        $inputFilter->add(
            $factory->createInput([
                'name' => 'password',
                'required' => true,
                'filters'=> [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    ['name' => NotEmpty::class],
                    [
                        'name' => StringLength::class,
                        'option' => [
                            'min' => 5,
                            'max' => 100,
                            'messages' => [
                                StringLength::TOO_SHORT => 'string_is_too_short',
                                StringLength::TOO_LONG => 'string_is_too_long',
                            ]
                        ]
                    ]
                ]
            ])
        );

        $inputFilter->add(
            $factory->createInput([
                'name' => 'csrf',
                'required' => true,
                'filters'=> [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    ['name' => NotEmpty::class],
                    [
                        'name' => Csrf::class,
                        'option' => [
                            'messages' => [
                                Csrf::NOT_SAME => 'session_excited_please_refill',
                            ]
                        ]
                    ]
                ]
            ])
        );

        return $inputFilter;
    }

    public function create(array $data): ResultInterface
    {
        $values = [
            'login' => $data['login'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ];

        $query = $this->sql->insert()->values($values);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        return $stmt->execute();
    }

    public function findByLogin(string $login): ?User
    {
        $query = $this->sql->select()->where(['login' => $login]);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        $handler = $stmt->execute()->current();

        if (!$handler)
            return null;

        $classMethod = new ClassMethodsHydrator();
        $entity = new User();

        $classMethod->hydrate($handler, $entity);

        return $entity;
    }
}