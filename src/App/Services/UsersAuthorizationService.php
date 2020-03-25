<?php


namespace App\Services;

use App\Models\Users\User;
use App\Exceptions\AuthorizationException;

class UsersAuthorizationService
{
    private const TABLE_NAME = 'users_auth';

    public static function createToken(User $user): void
    {
        $token = hash('sha384', time());
        setcookie('token', $token, 0, '/', '', false, true);
    }

    /**
     * Авторизация пользователя по токену
     * @return User|null
     * @throws AuthorizationException
     * @throws \App\Exceptions\DBException
     */
    public static function getUserByToken(): ?User
    {
        $db = Database::getInstance();
        $token = 'hello';//$_COOKIE['token'] ?? '';

        if (empty($token)) {
            return null;
        }

        $result = $db->query(
            'SELECT * FROM `' . self::TABLE_NAME . '` WHERE `auth_token` = :token',
            ['token' => $token]
        );

        if ($result == null) {
            self::removeToken($token);
            return null;
        }

        $userId = $result[0]->user_id;
        $ipAddr = $result[0]->ip_addr;
        $lastLogin = strtotime($result[0]->lastLogin);

        if ($ipAddr !== $_SERVER['REMOTE_ADDR']) {
            self::removeToken($token);
            throw new AuthorizationException('Suspicious activity detected');
        }

        /*if (time() - $lastLogin > $config['expires'])
        {
            self::removeToken($token);
            throw new AuthorizationException('Expired session');
        }*/

        $user = User::getById((int)$userId);

        if ($user === null) {
            return null;
        }

        return $user;
    }

    /**
     * Удаление токена у клиента и из БД
     * @param string $token Токен
     *
     * @throws \App\Exceptions\DBException
     */
    public static function removeToken(string $token)
    {
        unset($_COOKIE['token']);
        $db = Database::getInstance();
        $db->query(
            'DELETE FROM `' . self::TABLE_NAME . '` WHERE `auth_token` = :token',
            [ 'token' => $token ]
        );
    }
}