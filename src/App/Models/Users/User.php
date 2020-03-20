<?php


namespace App\Models\Users;

use App\Models\ActiveRecordEntity;

class User extends ActiveRecordEntity
{
    /** @var string */
    protected $nickname;

    /** @var string */
    protected $email;

    /** @var int */
    protected $isConfirmed;

    /** @var string */
    protected $role;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $authToken;

    /** @var string */
    protected $createdAt;

    /**
     * @return string Возвращает почту
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string Возвращает никнейм
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return string Возвращает название таблицы
     */
    protected static function getTableName(): string
    {
        return 'users';
    }
}