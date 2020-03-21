<?php


namespace App\Services;


class Database
{
    /** @var \PDO */
    private $pdo;
    /** @var null|Database Соединение с базой */
    private static $instance;
    /** @var int Количество соединений с базой */
    private static $instanceCount = 0;


    /**
     * @return Database|null
     */
    public static function getInstance(): ?Database
    {
        if(self::$instance === null)
        {
            self::$instance = new self();
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

        $this->pdo = new \PDO(
            'mysql:host='.$dbOptions['host'].';dbname='.$dbOptions['dbname'],
            $dbOptions['user'],
            $dbOptions['password']
        );
        $this->pdo->exec('SET NAMES UTF8');
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

        if(false === $result)
        {
            return null;
        }

        return  $sth->fetchAll(\PDO::FETCH_CLASS, $className);
    }
}