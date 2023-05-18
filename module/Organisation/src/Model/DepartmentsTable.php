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
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class DepartmentsTable extends AbstractApplicationTableGateway
{
    protected $adapter;
    protected $table = 'departments';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function getDepartmentFromFilter(): InputFilter
    {
        $inputFilter = new InputFilter();
        $factory = new Factory();

        $inputFilter->add(
            $factory->createInput([
                'name' => 'name',
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
            'name' => $data['name'],
        ];

        if (isset($data['department']) && (int) $data['department'] > 0) {
            $query = $this->sql->update()->set($values)->where(['id' => (int)$data['department']]);
        } else {
            $query = $this->sql->insert()->values($values);
        }

        $stmt = $this->sql->prepareStatementForSqlObject($query);

        return $stmt->execute();
    }

    public function getById(int $id): ?Department
    {
        $query = $this->sql->select()->where(['id' => $id]);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        $handler = $stmt->execute()->current();

        return $this->hydrate($handler, Department::class);
    }

    /**
     * @return array|Department[]
     */
    public function all(): array
    {
        $query = $this->sql->select()->order(['id' => 'desc']);
        $stmt = $this->sql->prepareStatementForSqlObject($query);

        return $this->collectionFromSet($stmt->execute(), Department::class);
    }
}