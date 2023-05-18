<?php
declare(strict_types = 1);

namespace Organisation\Model;

class Employee extends \ArrayObject
{
    public int $id;
    public string $last_name = '';
    public string $first_name = '';
    public string $middle_name = '';
    public string $birth_date;
    public string $birth_place;

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $last_name
     */
    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @param string $middle_name
     */
    public function setMiddleName(string $middle_name): void
    {
        $this->middle_name = $middle_name;
    }

    /**
     * @param string $birth_date
     */
    public function setBirthDate(string $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    /**
     * @param string $birth_place
     */
    public function setBirthPlace(string $birth_place): void
    {
        $this->birth_place = $birth_place;
    }

    public function getFullName(): string
    {
        return rtrim("{$this->last_name} {$this->first_name} {$this->middle_name}");
    }
}