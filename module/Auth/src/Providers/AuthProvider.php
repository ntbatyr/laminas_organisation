<?php

namespace Auth\Providers;

use Auth\Model\User;
use Auth\Model\UsersTable;

class AuthProvider
{
    private array $errors = [];

    private User $user;

    private UsersTable $table;
    public function __construct(UsersTable $table)
    {
        $this->table = $table;
        $this->createBaseUserIfNotExists();
    }
    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function login()
    {

    }


    /**
     * Создаем пользователя если еще нет
     * Не самый лучший способ создания записи..
     *
     * @return void
     */
    private function createBaseUserIfNotExists(): void
    {
        $user = $this->table->findByLogin('admin');
        if (empty($user) && empty($user->id)) {
            $user = new User();
            $user->login = 'admin';
            $user->password = 'admin123';
            $this->table->create($user);
        }
    }
}