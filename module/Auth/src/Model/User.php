<?php

namespace Auth\Model;

class User extends \ArrayObject
{
    public int $id;
    public string $login;
    public string $password;

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}