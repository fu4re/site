<?php


namespace App\Services;

use App\Models\Users\User;

class UsersActivationService
{
    private const TABLE_NAME = 'users_activation';

    /**
     * Создание кода активации для нового пользователя
     * @param User $user
     *
     * @return string
     * @throws \App\Exceptions\DBException
     */
    public static function createActivationCode(User $user): string
    {
        // Генерируем случайную последовательность символов, о функциях почитайте в документации
        $code = bin2hex(random_bytes(16));

        $db = Database::getInstance();
        $db->query(
            'INSERT INTO ' . self::TABLE_NAME . ' (user_id, activation_code) VALUES (:user_id, :code)',
            [
                'user_id' => $user->getId(),
                'code' => $code
            ]
        );

        return $code;
    }

    /**
     * Проверка кода активации, если успешно то активировать пользователя и аннулировать код
     * @param User $user Пользователь
     * @param string $code Код активации
     *
     * @return bool
     * @throws \App\Exceptions\DBException
     */
    public static function checkActivationCode(User $user, string $code): bool
    {
        $db = Database::getInstance();
        $result = $db->query(
            'SELECT * FROM ' . self::TABLE_NAME . ' WHERE user_id = :user_id AND activation_code = :code',
            [
                'user_id' => $user->getId(),
                'code' => $code
            ]
        );

        $ret = !empty($result);

        if ($ret)
        {
            self::removeActivationCode($code);
        }

        return $ret;
    }

    /**
     * Удалить код после его активации
     * @param string $code
     *
     * @throws \App\Exceptions\DBException
     */
    private static function removeActivationCode( string $code) : void{
        Database::getInstance()->query(
            'DELETE FROM ' . self::TABLE_NAME . ' WHERE activation_code = :code',
            [ 'code' => $code ]
        );
    }
}