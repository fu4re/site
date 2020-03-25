<?php


namespace App\Models\Users;

use App\Exceptions\InvalidArgumentException;
use App\Models\ActiveRecordEntity;

class User extends ActiveRecordEntity
{
    /** @var string */
    protected $nickname;
    /** @var string */
    protected $email;
    /** @var string */
    protected $passwordHash;
    /** @var string */
    protected $firstName;
    /** @var string */
    protected $secondName;
    /** @var bool */
    protected $useNick;
    /** @var bool */
    protected $isConfirmed;
    /** @var int */
    protected $role;
    /** @var string */
    protected $createdAt;

    /**
     * @return string Возвращает имя пользователя по его желанию
     */
    public function getName():string
    {
        if ($this->isConfirmed)
            return $this->nickname;
        else
            return $this->firstName;
    }

    /**
     * Регистрация пользователя
     * @param array $userData данные пользователя
     *
     * @throws InvalidArgumentException
     */
    public static function signUp(array $userData)
    {
        if (empty($userData['first_name'])) {
            throw new InvalidArgumentException('Не передано имя');
        }

        if (empty($userData['second_name'])) {
            throw new InvalidArgumentException('Не передана фамилия');
        }

        if (empty($userData['nickname'])) {
            throw new InvalidArgumentException('Не передан никнейм');
        }

        if (!preg_match('/[a-zA-Z0-9]+/', $userData['nickname'])) {
            throw new InvalidArgumentException('Никнейм может состоять только из символов латинского алфавита и цифр');
        }

        if (static::findOneByColumn('nickname', $userData['nickname']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким nickname уже существует');
        }

        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (static::findOneByColumn('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким email уже существует');
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email некорректен');
        }

        if (empty($userData['password0'])) {
            throw new InvalidArgumentException('Не передан пароль');
        }

        if (empty($userData['password1'])) {
            throw new InvalidArgumentException('Не потвержден пароль');
        }

        if (strlen($userData['password0']) < 8) {
            throw new InvalidArgumentException('Пароль должен быть не менее 8 символов');
        }

        if (!preg_match('/((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,64})/', $userData['password0'])) {
            throw new InvalidArgumentException('Пароль должен содержать цифру, строчную и заглавную букву');
        }

        if($userData['password0'] != $userData['password1']){
            throw new InvalidArgumentException('Пароли не совпадают');
        }

        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->firstName = $userData['first_name'];
        $user->secondName = $userData['second_name'];
        $user->passwordHash = password_hash($userData['password0'], PASSWORD_DEFAULT);
        $user->useNick = null;
        $user->isConfirmed = null;
        $user->role = null;
        $user->save();

        return $user;
    }

    protected static function getTableName(): string
    {
        return 'users';
    }
}