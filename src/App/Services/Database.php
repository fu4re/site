<?php
namespace App\Services;

use App\Exceptions\DBException;

class Database
{
    /** @var \PDO */
    private $pdo;
    /** @var null|Database Соединение с базой */
    private static $instance;
    /** @var int Количество соединений с базой */
    private static $instanceCount = 0;
    /** @var array Массив настроек */
    private static $dbOptions = [];

    /**
     * @param array $options Массив настроек
     * @throws DBException
     */
    public static function setOptions(array $options) : void
    {
        if( array_key_exists('host', $options) &&
            array_key_exists('dbname', $options) &&
            array_key_exists('user', $options) &&
            array_key_exists('password', $options)
        ) {
            if (is_string($options['host']) &&
                is_string($options['dbname']) &&
                is_string($options['user']) &&
                is_string($options['password']) )
            {
                self::$dbOptions = $options;
            } else {
                throw new DBException('Ошибка. Параметры не строки');
            }
        }else {
            throw new DBException('Ошибка. Не хватает параметров');
        }
    }

    /**
     * Вернуть экземпляр самого себя
     * @return self|null
     * @throws DBException
     */
    public static function getInstance(): ?Database
    {
        if(self::$instance === null)
        {
            if (count(self::$dbOptions) === 0 ) {
                throw new DBException('Ошибка. Не заданы параметры подключения');
            }else {
                self::$instance = new self();
            }
        }
        return self::$instance;
    }

    /**
     *  Установления соединения с БД
     */
    private function  __construct()
    {
        self::$instanceCount++;

        $dbOptions = (require __DIR__.'/../../settings.php')['db'];

        try{
            $this->pdo = new \PDO(
                'mysql:host='.$dbOptions['host'].';dbname='.$dbOptions['dbname'],
                $dbOptions['user'],
                $dbOptions['password']
            );
            $this->pdo->exec('SET NAMES UTF8');
        }catch (\PDOException $e) {
            throw new DBException('Ошибка при подключении к базе данных: '.$e->getMessage());
        }
    }

    /**
     * Вернуть id последеней обработанной записи
     * @return int
     */
    public function getLastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Выполнение запроса к БД
     * @param string $sql SQL-запрос
     * @param array $params Параметры
     * @param string $className Класс для извлечения ORM
     *
     * @return array|null Массив данных или null
     */
    public function query(string $sql, $params = [], string  $className = 'stdClass') : ?array
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);
        //$sth->debugDumpParams();
        if(false === $result)
        {
            return null;
        }

        return  $sth->fetchAll(\PDO::FETCH_CLASS, $className);
    }
}