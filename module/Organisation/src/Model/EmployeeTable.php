<?php
declare(strict_types = 1);

namespace Organisation\Model;

use Application\Model\Abstracts\AbstractApplicationTableGateway;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Csrf;
use Laminas\Validator\Date;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class EmployeeTable extends AbstractApplicationTableGateway
{
    protected $adapter;
    protected $table = 'employees';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function getEmployeeFromFilter(): InputFilter
    {
        $inputFilter = new InputFilter();
        $factory = new Factory();

        $stringValues = [
            'last_name',
            'first_name',
            'birth_place',
        ];

        foreach ($stringValues as $stringValue) {
            $inputFilter->add(
                $factory->createInput([
                    'name' => $stringValue,
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
                                'min' => 2,
                                'max' => 255,
                                'messages' => [
                                    StringLength::TOO_SHORT => 'string_is_too_short',
                                    StringLength::TOO_LONG => 'string_is_too_long',
                                ]
                            ]
                        ]
                    ]
                ])
            );
        }

        $inputFilter->add(
            $factory->createInput([
                'name' => 'middle_name',
                'required' => false,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    ['name' => NotEmpty::class],
                    [
                        'name' => StringLength::class,
                        'option' => [
                            'min' => 2,
                            'max' => 255,
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
                'name' => 'birth_date',
                'required' => false,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    ['name' => NotEmpty::class],
                    [
                        'name' => Date::class,
                        'option' => [
                            'format' => 'Y-m-d',
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

    public function save(array $data): ResultInterface
    {
        $values = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'birth_date' => $data['birth_date'],
            'birth_place' => $data['birth_place'],
        ];

        if (isset($data['employee']) && (int) $data['employee'] > 0) {
            $query = $this->sql->update()->set($values)->where(['id' => (int)$data['employee']]);
        } else {
            $query = $this->sql->insert()->values($values);
        }

        $stmt = $this->sql->prepareStatementForSqlObject($query);

        return $stmt->execute();
    }

    public function getById(int $id): ?Employee
    {
        $query = $this->sql->select()->where(['id' => $id]);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        $handler = $stmt->execute()->current();

        return $this->hydrate($handler, Employee::class);
    }

    /**
     * @return array|Employee[]
     */
    public function all(): array
    {
        $query = $this->sql->select()->order(['id' => 'desc']);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        return $this->collectionFromSet($stmt->execute(), Employee::class);
    }

    public function remove(int $employeeId): ResultInterface
    {
        $query = $this->sql->delete()->where(['id' => $employeeId]);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        return $stmt->execute();
    }
}