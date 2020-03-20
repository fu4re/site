<?php


namespace App\Models\Users;


class User
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string Вернуть имя
     */
    public function getName(): string
    {
        return $this->name;
    }
}