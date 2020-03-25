<?php


namespace App\Services;

use App\Models\Users\User;
use App\Exceptions\AuthorizationException;

class UsersAuthorizationService
{
    private const TABLE_NAME = 'users_auth';

    public static function createToken(User $user): void
    {
        $db = Database::getInstance();
        $result = $db->query('SELECT * FROM `' . self::TABLE_NAME . '` WHERE `user_id` = :id',
            ['id' => $user->getId()]
        );

        if ($result !== null)
        {
            foreach ($result as $row)
            {
                [ $id, $user_id, $a, $ip_addr] = array_values(get_object_vars($row));
                if ($ip_addr == $_SERVER['REMOTE_ADDR'])
                {
                    $token = self::refreshToken($user_id, $ip_addr);
                }
                self::newSession($user->getId(), $_SERVER['REMOTE_ADDR']);
            }
        }else{
            $token = self::generateToken();
            $db->query('INSERT INTO `'.self::TABLE_NAME.'`( `user_id`, `auth_token`, `ip_addr`) VALUES ()', [

            ]);
        }
        self::setCookie($token);
    }

    private static function setCookie(string $token): void
    {
        setcookie('token', $token, 0, '/', '', false, true);
    }

    /**
     * @param int|string $userId ID пользователя
     * @param string $ip_addr IPv4 адресс пользователя
     *
     * @return string
     * @throws \App\Exceptions\DBException
     */
    private static function refreshToken(int $userId,string  $ip_addr) : string
    {
        $db = Database::getInstance();
        $token = self::generateToken();
        $db->query('UPDATE `' . self::TABLE_NAME . '`SET `auth_token`=:token WHERE `user_id`=:id AND `ip_addr`=:ip',
            [
                'token' => $token,
                'id' => $userId,
                'ip' => $ip_addr
            ]
        );
        return $token;
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
        $token = $_COOKIE['token'] ?? '';

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

        [ $id, $userId, $authToken, $ip_addr, $lastLogin] = array_values(get_object_vars($result[0]));
        $lastLogin = strtotime($lastLogin);
        $refreshTime = 60*60*24;
        $expiresAt = 3 * $refreshTime;

        if($authToken == $token && $ip_addr == $_SERVER['REMOTE_ADDR'])
        {
            if (time()-$lastLogin > $expiresAt)
            {
                self::removeToken($token);
            }else if (time()-$lastLogin > $refreshTime)
            {
                self::setCookie(self::refreshToken($userId, $ip_addr));
            }
        }else{
            self::removeToken($token);
            throw new AuthorizationException('Suspicious activity detected');
        }

        $user = User::getById((int)$userId);
        if ($user == null) {
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
    private static function removeToken(string $token)
    {
        unset($_COOKIE['token']);
        $db = Database::getInstance();
        $db->query(
            'DELETE FROM `' . self::TABLE_NAME . '` WHERE `auth_token` = :token',
            [ 'token' => $token ]
        );
    }

    /**
     * Функция генерации токена
     * @param string $algo Алгоритм хеширования
     *
     * @return string Токен
     */
    private static function generateToken($algo = 'sha384') : string
    {
        return hash($algo, time());
    }
}